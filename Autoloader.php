<?php

namespace EzHttp;

class Autoloader
{
    protected static $_autoloadRootPath = '';

    public static function setRootPath($rootPath)
    {
        self::$_autoloadRootPath = $rootPath;
    }

    public static function loadByNamespace($name)
    {
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $name);
        if (strpos($name, 'EzHttp\\') === 0) {
            $classFile = __DIR__ . substr($classPath, strlen('EzHttp')) . '.php';
        } else {
            if (self::$_autoloadRootPath) {
                $classFile = self::$_autoloadRootPath . DIRECTORY_SEPARATOR . $classPath . '.php';
            }
            if (empty($classFile) || !is_file($classFile)) {
                $classFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $classPath . '.php';
            }
        }

        if (is_file($classFile)) {
            require_once($classFile);
            if (class_exists($classFile, false)) {
                return true;
            }
        }

        return false;
    }
}

spl_autoload_register('\EzHttp\Autoloader::loadByNamespace');