<?php

namespace Faxity\IP;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Controller for the /ip-api routes
 */
class APIController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Validate IP address and get info about the domain
     * @param string|null $ip
     *
     * @return array
     */
    private function validateIP(?string $ip) : array
    {
        $status = 200;
        $data = (object)[];

        if (empty($ip)) {
            $data->message = "Ingen IP address skickades.";
            $status = 400;
        } else {
            $data->ip = $ip;
            $data->valid = !!filter_var($ip, FILTER_VALIDATE_IP);

            // If domain not found it returns the IP
            $host = $data->valid ? gethostbyaddr($ip) : null;
            $data->domain = $host != $ip ? $host : null;
        }

        return [ (array)$data, $status ];
    }


    /**
     * Handles / for the controller
     *
     * @return array
     */
    public function indexActionPost() : array
    {
        $ip = $this->di->request->getPost("ip");

        return $this->validateIP($ip);
    }
}
