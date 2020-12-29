<?php


namespace zhos;


abstract class BaseController
{
    /**
     * @var \Smarty
     */
    protected $view;

    public function __construct()
    {
        $this->view = new \Smarty();
        $this->view->left_delimiter = '<{';
        $this->view->right_delimiter = '}>';
        $this->view->caching = 1;
        $this->view->setTemplateDir(APP_ROOT . '/views');
        $this->view->setCompileDir(APP_ROOT . '/templates_c');
        $this->view->setCacheDir(APP_ROOT . '/cache');
        $this->view->setConfigDir(APP_ROOT . '/configs');
    }
}