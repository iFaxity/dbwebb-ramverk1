<?php

namespace Faxity\Weather;

use Test\ControllerTestCase;

/**
 * Test Weather Controller.
 */
class ControllerTest extends ControllerTestCase
{
    protected $className = Controller::class;


    /**
     * Test the route "index".
     */
    public function testIndexAction() : void
    {
        $this->di->request->setGet("location", "-18.67, 48.99");

        $res = $this->controller->indexActionGet();
        $this->assertInstanceOf(\Anax\Response\Response::class, $res);

        $body = $res->getBody();
        $this->assertContains("| ramverk1</title>", $body);
        $this->assertContains("<h2>Dagliga prognoser</h2>", $body);
    }


    /**
     * Test the route "index".
     */
    public function testIndexActionFail() : void
    {
        $this->di->request->setGet("location", "");

        $res = $this->controller->indexActionGet();
        $this->assertInstanceOf(\Anax\Response\Response::class, $res);

        $body = $res->getBody();
        $this->assertContains("| ramverk1</title>", $body);

        // Ensure the error flash message shows to the user
        $this->assertContains("<div class=\"message err\">", $body);
    }


    /**
     * Test the route "docs".
     */
    public function testDocsAction() : void
    {
        $res = $this->controller->docsActionGet();
        $this->assertInstanceOf(\Anax\Response\Response::class, $res);

        $body = $res->getBody();
        $this->assertContains("<h2>Väder API Documentation</h2>", $body);
    }
}
