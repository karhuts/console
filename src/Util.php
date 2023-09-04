<?php
declare(strict_types=1);
namespace karthus\console;

use Doctrine\Inflector\InflectorFactory;

class Util
{
    /**
     * @param string $name
     * @return array|string
     */
    public static function nameToNamespace(string $name  = ""): array|string
    {
        $namespace = ucfirst($name);
        $namespace = preg_replace_callback(['/-([a-zA-Z])/', '/(\/[a-zA-Z])/'], function ($matches) {
            return strtoupper($matches[1]);
        }, $namespace);
        return str_replace('/', '\\' ,ucfirst($namespace));
    }

    /**
     * @param string $class
     * @return array|string|null
     */
    public static function classToName(string $class = ""): array|string|null
    {
        $class = lcfirst($class);
        return preg_replace_callback(['/([A-Z])/'], function ($matches) {
            return '_' . strtolower($matches[1]);
        }, $class);
    }

    /**
     * @param string $class
     * @return string
     */
    public static function nameToClass(string $class): string
    {
        $class = preg_replace_callback(['/-([a-zA-Z])/', '/_([a-zA-Z])/'], function ($matches) {
            return strtoupper($matches[1]);
        }, $class);

        if (!($pos = strrpos($class, '/'))) {
            $class = ucfirst($class);
        } else {
            $path = substr($class, 0, $pos);
            $class = ucfirst(substr($class, $pos + 1));
            $class = "$path/$class";
        }
        return $class;
    }

    /**
     * @param $base_path
     * @param $name
     * @param false $return_full_path
     * @return false|string
     */
    public static function guessPath($base_path, $name, false $return_full_path = false): false|string
    {
        if (!is_dir($base_path)) {
            return false;
        }
        $names = explode('/', trim(strtolower($name), '/'));
        $realname = [];
        $path = $base_path;
        foreach ($names as $name) {
            $finded = false;
            foreach (scandir($path) ?: [] as $tmp_name) {
                if (strtolower($tmp_name) === $name && is_dir("$path/$tmp_name")) {
                    $path = "$path/$tmp_name";
                    $realname[] = $tmp_name;
                    $finded = true;
                    break;
                }
            }
            if (!$finded) {
                return false;
            }
        }
        $realname = implode(DIRECTORY_SEPARATOR, $realname);
        return $return_full_path ? realpath($base_path . DIRECTORY_SEPARATOR . $realname) : $realname;
    }
}
