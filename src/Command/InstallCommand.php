<?php
declare(strict_types=1);

namespace karthus\console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function karthus\app_path;
use function karthus\base_path;

class InstallCommand extends Command
{
    protected static $defaultName = 'install';
    protected static $defaultDescription = 'Install karthus';


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dest = base_path() . "/karthus";
        if (is_dir($dest)) {
            echo "Installation failed, please remove directory $dest\n";
            return self::FAILURE;
        }
        copy(__DIR__ . "/../bin/karthus", $dest);
        chmod(base_path() . "/karthus", 0755);
        return self::SUCCESS;
    }

}
