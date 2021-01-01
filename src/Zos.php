<?php


namespace zhos;


use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Zos
{
    const VERSION = '1.0.0';

    /**
     * @var Zos
     */
    private static $service = null;

    private static $name = 'demo';

    /**
     * @var Logger
     */
    private static $log;

    private $controller;

    private $action;

    public function __construct()
    {
        try {
            Zos::$log = new Logger(Zos::getName());
            Zos::$log->pushHandler(new StreamHandler(APP_ROOT . '/' . Zos::getName() . '.log', Logger::DEBUG));
            Zos::$log->pushHandler(new FirePHPHandler());

            $route = isset($_GET['r']) ? $_GET['r'] : 'index/index';
            $this->controller = explode('/', $route)[0];
            $this->action = explode('/', $route)[1];
            $class = Zos::getName() .'\controller\\'.ucfirst($this->controller).'Controller';
            call_user_func(array((new $class), 'action'.ucfirst($this->action)));
        } catch (\Exception $e) {
            Zos::$log->error($e->getMessage());
        }
    }

    public static function setName(string $namespace) : void
    {
        self::$name = $namespace;
    }

    public static function getName() : string
    {
        return self::$name;
    }

    public static function getLog() : Logger
    {
        return self::$log;
    }

    public static function run() : Zos
    {
        if (!self::$service instanceof Zos) {
            self::$service = new Zos();
        }

        return self::$service;
    }
}