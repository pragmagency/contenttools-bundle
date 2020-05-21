<?php

namespace Pragmagency\ContentTools\Command;

use Pragmagency\ContentTools\Installer\ContenttoolsInstallerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class PragmagencyContenttoolsInstallCommand extends Command
{
    protected static $defaultName = 'pragmagency:contenttools:install';

    private $installer;

    public function __construct(ContenttoolsInstallerInterface $installer)
    {
        parent::__construct();

        $this->installer = $installer;
    }

    protected function configure()
    {
        $this->setDescription('Installs contenttools.js');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->section('Installing contenttools.js');

        $this->installer->install();

        $io->success('contenttools.js installed');

        return 0;
    }
}
