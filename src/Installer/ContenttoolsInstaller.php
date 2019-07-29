<?php

namespace Pragmagency\ContentTools\Installer;

use Pragmagency\ContentTools\Configuration\ContenttoolsConfigurationInterface;

final class ContenttoolsInstaller implements ContenttoolsInstallerInterface
{
    const DOWNLOAD_URL = 'https://github.com/GetmeUK/ContentTools/archive/%s.zip';

    /** @var ContenttoolsConfigurationInterface */
    private $configuration;
    /** @var string */
    private $rootDir;

    public function __construct(
        ContenttoolsConfigurationInterface $configuration,
        string $rootDir
    ) {
        $this->configuration = $configuration;
        $this->rootDir = $rootDir;
    }

    public function install(): bool
    {
        if ($this->isInstalled()) {
            return false;
        }

        $this->checkInstallationDir();

        $version = $this->getVersion();
        $this->extract($this->download($version));

        return true;
    }

    private function isInstalled(): string
    {
        return file_exists(sprintf('%s/content-tools.min.js', $this->getInstallationDir()));
    }

    private function getInstallationDir(): string
    {
        return sprintf('%s/vendor/pragmagency/contenttools-bundle/src/Resources/public/lib', $this->rootDir);
    }

    private function download(string $version): string
    {
        $url = $this->getDownloadUrl($version);

        $zip = @file_get_contents($url);

        if (false === $zip) {
            throw new \Exception(sprintf('Can\'t download contenttools from "%s"', $url));
        }

        $path = (string) tempnam(sys_get_temp_dir(), sprintf('contenttools-%s.zip', $version));

        if (!@file_put_contents($path, $zip)) {
            throw new \Exception(sprintf('Can\'t write contenttools to "%s"', $path));
        }

        return $path;
    }

    private function getDownloadUrl(string $version): string
    {
        return sprintf(self::DOWNLOAD_URL, $version);
    }

    private function checkInstallationDir()
    {
        $installationDir = $this->getInstallationDir();
        $imagesDir = sprintf('%s/images', $installationDir);

        if (!is_dir($installationDir)) {
            mkdir($installationDir, 0777, true);
        }
        if (!is_dir($imagesDir)) {
            mkdir($imagesDir, 0777, true);
        }
    }

    private function getVersion(): string
    {
        $composerData = json_decode(file_get_contents(sprintf('%s/../../composer.json', __DIR__)), true);

        if (isset($composerData['extra']) && isset($composerData['extra']['contenttools_version'])) {
            return $composerData['extra']['contenttools_version'];
        }

        throw new \InvalidArgumentException();
    }

    private function extract(string $path): void
    {
        $zip = new \ZipArchive();
        $zip->open($path);

        $installationDir = $this->getInstallationDir();

        for ($i = 0; $i < $zip->numFiles; ++$i) {
            $filename = $zip->getNameIndex($i);

            if (1 === preg_match('~/build/(.+[^/])$~', $filename, $matches)) {
                $this->extractFile($filename, $matches[1], $path, $installationDir);
            }
        }

        $zip->close();

        if (!@unlink($path)) {
            throw new \Exception(sprintf('Can\'t remove the contenttools zip "%s".', $path));
        }
    }

    private function extractFile(string $file, string $rewrite, string $origin, string $target): void
    {
        $from = sprintf('zip://%s#%s', $origin, $file);
        $to = sprintf('%s/%s', $target, $rewrite);

        if (!is_dir($target) && !@mkdir($target, 0777, true)) {
            throw new \Exception(sprintf('Unable to create the directory "%s".', $target));
        }

        if (!@copy($from, $to)) {
            throw new \Exception(sprintf('Unable to extract the file "%s" to "%s".', $file, $to));
        }
    }
}
