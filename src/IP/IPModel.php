<?php

namespace Faxity\IP;

/**
 * Handles IP operations
 */
class IPModel
{
    /**
     * @var string IPSTACK_URL The base URL of ipstack's api.
     * @var string IPSTACK_ACCESS_KEY The ipstack access key.
     */
    private const IPSTACK_URL = "http://api.ipstack.com";
    private const IPSTACK_ACCESS_KEY = "e0318d38996064dac9467a1dd467e419";


    /**
     * Validate IP address and get info about the domain
     * @param string $url URL to send request to
     * @param string|null $params Optional query parameters as an array or object
     *
     * @return object
     */
    private function requestJSON(string $url, $params = null) : ?object
    {
        if (is_object($params) || is_array($params)) {
            $query = http_build_query($params);

            if (!empty($query)) {
                $url .= "?$query";
            }
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $body = curl_exec($curl);
        curl_close($curl);

        // Parse response as json
        return is_string($body) ? json_decode($body) : null;
    }



    /**
     * Gets the IP address of the client
     *
     * @return string
     */
    public function getAddress() : string
    {
        return $_SERVER["REMOTE_ADDR"] ?? "";
    }


    /**
     * Validate IP address and get info about the domain, null is returned if empty
     * @param string|null $ip IP to Validate
     *
     * @return object|null
     */
    public function validate(?string $ip) : ?object
    {
        if (empty($ip)) {
            return null;
        }

        $data = (object)[
            "ip" => $ip,
            "valid" => false,
            "type" => null,
            "domain" => null,
            "region" => null,
            "country" => null,
            "location" => null,
        ];

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $host = gethostbyaddr($ip);
            $res = $this->requestJSON(self::IPSTACK_URL . "/$ip", [
                "access_key" => self::IPSTACK_ACCESS_KEY,
            ]);

            $data->ip = $ip;
            $data->valid = true;
            $data->type = $res->type;
            $data->domain = $host != $ip ? $host : null;
            $data->region = $res->region_name;
            $data->country = $res->country_name;

            if (isset($res->latitude, $res->longitude)) {
                $data->location = (object)[
                    "latitude" => $res->latitude,
                    "longitude" => $res->longitude,
                ];
            }
        }

        return $data;
    }
}
