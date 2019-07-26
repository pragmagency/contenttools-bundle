<?php

namespace Pragmagency\ContentTools\Installer;

use Pragmagency\ContentTools\Configuration\ContenttoolsConfigurationInterface;

final class ContenttoolsInstaller implements ContenttoolsInstallerInterface
{
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

        $this->copy();

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

    private function getContenttoolsDistDir(): string
    {
        return sprintf('%s/vendor/getmeuk/contenttools', $this->rootDir);
    }

    private function copy()
    {
        $installationDir = $this->getInstallationDir();

        if (!is_dir($installationDir)) {
            mkdir($installationDir, 0777, true);
        }

        // @todo
    }
}
