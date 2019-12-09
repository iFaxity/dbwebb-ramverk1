<?php

namespace Faxity\Navbar;

use Anax\DI\DIFactoryConfig;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;

/**
 * A DI variant with magic methods..
 */
class DIMagicTest extends TestCase
{
    private $di;
    private $navbar;


    public function setUp(): void
    {
        global $di;

        $di = new DIMagic();
        $di->loadServices([
            "services" => [
                "request" => [
                    "shared" => true,
                    "callback" => function () {
                        $obj = new \Anax\Request\Request();
                        $obj->setServer("SCRIPT_NAME", "/app.php");
                        $obj->setServer("REQUEST_URI", "/app.php/");
                        $obj->init();

                        return $obj;
                    },
                ],
                "url" => [
                    "shared" => true,
                    "callback" => function () use ($di) {
                        $url = new \Anax\Url\Url();
                        $url->setSiteUrl($di->request->getSiteUrl());
                        $url->setBaseUrl($di->request->getBaseUrl());
                        $url->setStaticSiteUrl($di->request->getSiteUrl());
                        $url->setStaticBaseUrl($di->request->getBaseUrl());
                        $url->setScriptName($di->request->getScriptName());
                        $url->setUrlType(\Anax\Url\Url::URL_CLEAN);

                        return $url;
                    },
                ],
            ],
        ]);

        $this->di = $di;
        $this->navbar = new Navbar();
        $this->navbar->setDI($di);
    }


    public function tearDown(): void
    {
        global $di;

        $di = null;
        $this->di = null;
        $this->navbar = null;
    }


    public function testCheck(): void
    {
        $this->di->request->setServer("REQUEST_URI", "/app.php/hello");
        $this->di->request->init();

        $this->assertTrue($this->navbar->check("hello"));
        $this->assertFalse($this->navbar->check("world"));

        $this->di->request->setServer("REQUEST_URI", "/app.php/world");
        $this->di->request->init();

        $this->assertTrue($this->navbar->check("world"));
        $this->assertFalse($this->navbar->check("hello"));
    }


    public function testCreateMenu(): void
    {
        $items = [
            [
                "text"  => "Hem",
                "url"   => "",
                "title" => "Homepage"
            ],
            [
                "text"  => "About",
                "url"   => "about",
                "title" => "About us",
            ]
        ];

        list($html, $active) = $this->navbar->createMenu($items);
        $this->assertIsString($html);
        $this->assertTrue($active);

        $this->di->request->setServer("REQUEST_URI", "/app.php/hello");
        $this->di->request->init();

        $items[] = [
            "text"  => "Foo",
            "url"   => "foo",
            "title" => "foo",
            "submenu" => [
                [
                    "text"  => "bar",
                    "url"   => "bar",
                    "title" => "bar",
                ]
            ]
        ];

        list($html, $active) = $this->navbar->createMenu($items);
        $this->assertIsString($html);
        $this->assertFalse($active);
    }


    public function testCreateMenuWithSubmenus(): void
    {
        $items = [
            [
                "text"  => "Hem",
                "url"   => "",
                "title" => "Homepage"
            ],
            [
                "text"  => "About",
                "url"   => "about",
                "title" => "About us",
            ]
        ];

        $html= $this->navbar->createMenuWithSubMenus([
            "items" => $items,
        ]);
        $this->assertIsString($html);

        $html= $this->navbar->createMenuWithSubMenus([
            "class" => "someclass",
            "items" => $items,
        ]);
        $this->assertIsString($html);
    }
}
