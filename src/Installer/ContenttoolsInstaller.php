<?php

namespace Pragmagency\ContentTools\Installer;

use Pragmagency\ContentTools\Configuration\ContenttoolsConfigurationInterface;

final class ContenttoolsInstaller implements ContenttoolsInstallerInterface
{
    const BUILD_PATH_URL = 'https://raw.githubusercontent.com/GetmeUK/ContentTools/%s/build/%s';

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

        $this->downloadToInstallationDir($this->getVersion());

        return true;
    }

    private function isInstalled(): string
    {
        return file_exists(sprintf('%scontent-tools.min.js', $this->getInstallationDir()));
    }

    private function getInstallationDir(): string
    {
        return sprintf('%s/public/%s', $this->rootDir, $this->configuration->getConfig()['base_path']);
    }

    private function downloadToInstallationDir(string $version)
    {
        $installationDir = $this->getInstallationDir();

        $this->checkInstallationDir($installationDir);

        foreach (['content-tools.min.css', 'content-tools.min.js'] as $file) {
            file_put_contents(
                sprintf('%s/%s', $installationDir, $file),
                file_get_contents(sprintf(self::BUILD_PATH_URL, $version, $file))
            );
        }
    }

    private function checkInstallationDir(string $dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
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
}
