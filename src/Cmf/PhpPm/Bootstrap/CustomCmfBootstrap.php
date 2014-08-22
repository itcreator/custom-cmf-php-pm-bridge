<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
 
namespace Cmf\PhpPm\Bootstrap;

use PHPPM\Bootstraps\BootstrapInterface;

use Stack\Builder;

/**
 * This class is bootstrap for the Custom CMF.
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CustomCmfBootstrap implements BootstrapInterface
{
    /**
     * @var string|null The application environment
     */
    protected $appEnv;

    /**
     * Instantiate the bootstrap, storing the $appenv
     */
    public function __construct($appEnv)
    {
        $this->appEnv = $appEnv;
    }

    /**
     * Create a Custom CMF application
     *
     * @return \Cmf\System\Application
     */
    public function getApplication()
    {
        define('ROOT', getcwd() . '/');

        if (!class_exists('Cmf\System\Application')) {
            require ROOT . 'vendor/autoload.php';
        }

        require ROOT . 'boot/bootstrap_www.php';

        $application = \Cmf\System\Application::getInstance();
        $application->init();

        return $application;
    }

    /**
     * Return the StackPHP stack.
     */
    public function getStack(Builder $stack)
    {
        return $stack;
    }
}
