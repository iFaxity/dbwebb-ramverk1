<?php

namespace Faxity\IP;

use PHPUnit\Framework\TestCase;

/**
 * Test the IPv6 Controller.
 */
class IPModelTest extends TestCase
{
    private $ip;


    /**
     * Setup for every test case
     * @return void.
     */
    public function setUp() : void
    {
        $this->ip = new IPModel();
    }


    /**
     * Teardown for every test case
     * @return void.
     */
    public function tearDown() : void
    {
        $this->ip = null;
    }


    /**
     * Test validate method
     */
    public function testValidateIPV4()
    {
        $data = $this->ip->validate("194.47.150.9");

        $this->assertEquals("194.47.150.9", $data->ip);
        $this->assertTrue($data->valid);
        $this->assertEquals("ipv4", $data->type);
        $this->assertIsString($data->domain);
        $this->assertIsString($data->region);
        $this->assertIsString($data->country);
        $this->assertIsObject($data->location);
    }


    /**
     * Test validate method
     */
    public function testValidateIPV6()
    {
        $data = $this->ip->validate("2001:db8::1");

        $this->assertEquals("2001:db8::1", $data->ip);
        $this->assertTrue($data->valid);
        $this->assertEquals("ipv6", $data->type);
        // Not a real ip, the data below should be null
        $this->assertNull($data->domain);
        $this->assertNull($data->region);
        $this->assertNull($data->country);
        $this->assertNull($data->location);
    }


    /**
     * Test validate method
     */
    public function testValidateFail()
    {
        $data = $this->ip->validate("");

        $this->assertNull($data);
    }


    /**
     * Test getAddress method
     */
    public function testGetAddress()
    {
        $_SERVER["REMOTE_ADDR"] = "10.20.30.40";
        $addr = $this->ip->getAddress();

        $this->assertEquals($addr, "10.20.30.40");
    }
}
