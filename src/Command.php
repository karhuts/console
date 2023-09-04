<?php
declare(strict_types=1);

namespace karthus\console;

use Symfony\Component\Console\Command\Command as sCommand;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Console\Application;

class Command extends Application
{
    /**
     * @return Command
     */
    public function installInternalCommands(): self
    {
        $this->installCommands(__DIR__ . '/Commands',
            'karthus\console\Commands');

        return $this;
    }

    /**
     * @param $path
     * @param string $namespace
     * @return Command
     */
    public function installCommands($path, string $namespace = 'app\command'): self
    {
        $dir_iterator = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($dir_iterator);
        foreach ($iterator as $file) {
            /** @var SplFileInfo $file */
            if (str_starts_with($file->getFilename(), '.')) {
                continue;
            }
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $relativePath = str_replace(str_replace('/', '\\', $path . '\\'), '', str_replace('/', '\\', $file->getRealPath()));
            $realNamespace = trim($namespace . '\\' . trim(dirname(str_replace('\\', DIRECTORY_SEPARATOR, $relativePath)), '.'), '\\');
            $realNamespace =  str_replace('/', '\\', $realNamespace);
            $class_name = trim($realNamespace . '\\' . $file->getBasename('.php'), '\\');
            if (!class_exists($class_name) || !is_a($class_name, sCommand::class, true) || (new \ReflectionClass($class_name))->isAbstract()) {
                continue;
            }
            $this->add($class_name);
        }

        return $this;
    }
}
