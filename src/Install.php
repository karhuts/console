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
        copy(__DIR__ . "/karthus", $dest);
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
