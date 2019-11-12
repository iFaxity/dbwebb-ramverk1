<?php

namespace Test;

use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;

/**
 * Just a wrapper so we dont need to add same code in all
 * of the controllers test classes
 */
class ControllerTestCase extends TestCase
{
    /**
     * @var $controller Anax Controller class
     * @var $di Dependency injector
     * @var $dclassName Controller class name
     */
    protected $controller;
    protected $di;
    protected $className;

    /**
     * Setup for every test case
     * @return void.
     */
    public function setUp() : void
    {
        global $di;

        // Create dependency injector with the controller
        $di = new DIMagic();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $di->cache->setPath(ANAX_INSTALL_PATH . "/cache/test");

        $class_ctor = $this->className;
        $this->controller = new $class_ctor();
        $this->controller->setDI($di);
        $this->di = $di;

        if (method_exists($this->controller, "initialize")) {
            $this->controller->initialize();
        }
    }

    /**
     * Teardown for every test case
     * @return void.
     */
    public function tearDown() : void
    {
        global $di;

        $di = null;
        $this->controller = null;
        $this->di = null;
    }
}
