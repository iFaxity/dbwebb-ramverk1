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
        "2001:db8::1",// => true,
        "2a03:2880:f00a:8:face:b00c:0:2",// => true,
        "1200::ABYX:1234::2552:7777:1313",// => false,
        "194.47.150.9",// => true,
        "192.168.1.20",// => true,
        "83.230.116.044",// => false,
    ];

    private $examples;


    private function validateIP(?string $ip) : object
    {
        $data = (object)[];

        if (!empty($ip)) {
            $data->valid = !!filter_var($ip, FILTER_VALIDATE_IP);

            // If domain not found it returns the IP
            $host = $data->valid ? gethostbyaddr($ip) : null;
            $data->domain = $host != $ip ? $host : null;
        }

        return $data;
    }


    public function initialize()
    {
        $this->examples = (object) [];

        // Create example responses
        $this->examples->err = esc(json_encode([
            "message" => "Ingen IP address skickades.",
        ], JSON_PRETTY_PRINT));

        $this->examples->ok = esc(json_encode([
            "ip" => "194.47.150.9",
            "valid" => true,
            "domain" => "dbwebb.se",
        ], JSON_PRETTY_PRINT));
    }


    /**
     * This is the index method action, it handles:
     *
     * @return object
     */
    public function indexActionGet() : object
    {
        // Deal with the action and return a response.
        $ip = $this->di->request->getGet("ip");
        $data = $this->validateIP($ip);
        $baseUrl = $this->di->request->getBaseUrl();

        $this->di->page->add("ip/index", [
            "ip"      => $ip,
            "valid"   => $data->valid ?? null,
            "domain"  => $data->domain ?? null,
            "addrs"   => $this::TEST_ADDRS,
            "apiUrl"  => $baseUrl . "/ip-api",
            "examples" => $this->examples,
        ]);

        return $this->di->page->render([
            "title" => "IP validerare",
        ]);
    }
}
