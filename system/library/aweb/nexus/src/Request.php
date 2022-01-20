<?php

namespace Aweb\Nexus;

use Aweb\Nexus\Http\RequestInstance;
use Exception;

class Request
{
    /**
     * Opencart registry
     */
    protected static $instance;

    /**
     * SET OpenCart registry as core
     */
    private function __construct() {}

    /**
     * SET OpenCart registry as core, called only once
     */

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new RequestInstance(Nexus::getInstance());
        }

        return self::$instance;
    }

    public static function __callStatic($name, $arguments = [])
    {
        if (!method_exists(self::getInstance(), $name)) {
            throw new Exception("Method $name does not exists on ". get_class(self::getInstance()));
        }

        return call_user_func_array([self::getInstance(), $name], $arguments);
    }
}