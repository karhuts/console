<?php

namespace karthus\console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function karthus\base_path;

class VersionCommand extends Command
{

    protected static $defaultName = 'version';
    protected static $defaultDescription = 'Show karthus version';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $installed_file = base_path() . '/vendor/composer/installed.php';
        if (is_file($installed_file)) {
            $version_info = include $installed_file;
        }
        $version = $version_info['versions']['karthus/framework']['pretty_version'] ?? '';
        $output->writeln("karthus-framework $version");
        return self::SUCCESS;
    }
}
