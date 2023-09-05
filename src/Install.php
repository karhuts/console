<?php
declare(strict_types=1);
namespace karthus\console;

use function karthus\base_path;

class Install {

    /**
     * @param $event
     * @return void
     */
    public static function install($event): void{
        $dest = base_path() . "/karthus";
        if (is_dir($dest)) {
            echo "Installation failed, please remove directory $dest\n";
            return;
        }
        $__ = <<<EOT
#!/usr/bin/env php
<?php
\$file = null;
\$vendor = [
    __DIR__ . '/../../../autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php'
];

use karthus\console\Command;
use function karthus\app_path;
define('BASE_PATH', basename(__DIR__));

foreach (\$vendor as \$file) {
    if (file_exists(\$file)) {
        require_once(\$file);
        break;
    }
}

if(!file_exists(\$file)){
    die("include composer autoload.php fail\n");
}

\$cli = new Command();
\$cli->setName('karthus cli');
\$cli->installInternalCommands()
    ->installCommands(app_path() . "/command");

\$cli->run();
EOT;

        file_put_contents($dest, $__);
        chmod(base_path() . "/karthus", 0755);
    }

    /**
     * @return void
     */
    public static function update(): void {
        static::install();
    }


    /**
     * Uninstall
     * @return void
     */
    public static function uninstall() : void
    {
        if (is_file(base_path()."/karthus")) {
            unlink(base_path() . "/karthus");
        }
    }
}
