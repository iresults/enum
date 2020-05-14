<?php
declare(strict_types=1);

namespace Iresults\Enum\Tests;

class Bootstrap
{
    public function run()
    {
        $this->registerCustomAutoloader();
        $this->registerComposerAutoloader();
    }

    private function registerComposerAutoloader()
    {
        if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
            require_once __DIR__ . '/../vendor/autoload.php';
        } elseif (file_exists(__DIR__ . '/../../../../vendor/autoload.php')) {
            require_once __DIR__ . '/../../../../vendor/autoload.php';
        } elseif (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
            require_once __DIR__ . '/../../vendor/autoload.php';
        } else {
            throw new \Exception('Could not find composer autoloader');
        }
    }

    private function registerCustomAutoloader()
    {
        spl_autoload_register(
            function ($className) {
                echo $className . PHP_EOL;
                $pathRelative = str_replace(
                    ['_', '\\'],
                    DIRECTORY_SEPARATOR,
                    $className
                );
                $classFile = __DIR__ . '/../Classes/' . $pathRelative . '.php';
                echo $classFile . PHP_EOL;
                if (file_exists($classFile)) {
                    require_once $classFile;
                }
            }
        );
    }
}

(new Bootstrap())->run();
