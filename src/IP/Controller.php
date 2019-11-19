<?php

namespace Faxity\IP;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Controller for the /ip routes
 */
class Controller implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    private const TEST_ADDRS = [
        "2001:db8::1",
        "2a03:2880:f00a:8:face:b00c:0:2",
        "1200::ABYX:1234::2552:7777:1313",
        "194.47.150.9",
        "192.168.1.20",
        "83.230.116.044",
    ];


    /**
     * @var object $examples API response examples
     * @var IPModel $ip IPModel class
     */
    private $examples;
    private $ip;


    /**
     * Initializer for the class
     */
    public function initialize()
    {
        $this->ip = new IPModel();
        $this->examples = (object) [];

        // Create example responses
        $this->examples->err = esc(json_encode([
            "message" => "Ingen IP address skickades.",
        ], JSON_PRETTY_PRINT));

        $this->examples->ok = esc(json_encode([
            "ip" => "194.47.150.9",
            "valid" => true,
            "domain" => "dbwebb.se",
            "type" => "ipv4",
            "region" => "Blekinge",
            "country" => "Sweden",
            "location" => [
                "latitude"  => 56.16122055053711,
                "longitude" => 15.586899757385254,
            ],
        ], JSON_PRETTY_PRINT));
    }


    /**
     * Handles / for the controller
     *
     * @return object
     */
    public function indexActionGet() : object
    {
        // Deal with the action and return a response.
        $ip = $this->di->request->getGet("ip");

        if (empty($ip)) {
            $ip = $this->ip->getAddress();
        }

        $res = (array) $this->ip->validate($ip);

        $this->di->page->add("ip/index", $res);
        $this->di->page->add("ip/api", [
            "ip"       => $ip,
            "addrs"    => $this::TEST_ADDRS,
            "apiUrl"   => $this->di->url->create("ip-api"),
            "examples" => $this->examples,
        ]);

        return $this->di->page->render([
            "title" => "IP validerare",
        ]);
    }
}
