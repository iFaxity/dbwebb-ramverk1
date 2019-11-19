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
     * @var IPModel $ip IPModel class
     */
    private $ip;


    /**
     * Initializer for the class
     */
    public function initialize()
    {
        $this->ip = new IPModel();
    }


    /**
     * Handles / for the controller
     *
     * @return array
     */
    public function indexActionPost() : array
    {
        $ip = $this->di->request->getPost("ip");
        $res = $this->ip->validate($ip);

        if (is_null($res)) {
            $json = [ "message" => "Ingen IP address skickades." ];
            return [ $json, 400 ];
        }

        return [ (array) $res, 200 ];
    }
}
