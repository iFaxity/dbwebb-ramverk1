<?php

namespace Faxity\IP;

/**
 * Test the IPv6 Controller.
 */
class ControllerTest extends \Test\ControllerTestCase
{
    protected $className = Controller::class;


    /**
     * Test the route "index".
     */
    public function testIndexAction() : void
    {
        $res = $this->controller->indexActionGet();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();
        $this->assertContains("| ramverk1</title>", $body);
    }


    /**
     * Test the route "index".
     */
    public function testIndexActionInvalid() : void
    {
        $this->di->request->setGet("ip", "1200::AB00:1234::2552:7777:1313");
        $res = $this->controller->indexActionGet();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();
        $this->assertContains("ogiltig", $body);
    }


    /**
     * Test the route "index".
     */
    public function testIndexActionValid() : void
    {
        $this->di->request->setGet("ip", "2001:db8::aaaa:0:0:1");
        $res = $this->controller->indexActionGet();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();
        $this->assertContains("giltig", $body);
    }
}