<?php


namespace zhos;


class Zos
{
    const VERSION = '1.0.0';

    /**
     * @var Zos
     */
    private static $service = null;

    private $controller;

    private $action;

    public function __construct()
    {
        try {
            $route = isset($_GET['r']) ? $_GET['r'] : 'index/index';
            $this->controller = explode('/', $route)[0];
            $this->action = explode('/', $route)[1];
            $class = APP_NAMESPACE .'\controller\\'.ucfirst($this->controller).'Controller';
            call_user_func(array((new $class), 'action'.ucfirst($this->action)));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function run()
    {
        if (!self::$service instanceof Zos) {
            self::$service = new Zos();
        }

        return self::$service;
    }
}