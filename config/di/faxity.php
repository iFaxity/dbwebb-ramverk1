<?php

/**
 * Configuration file for DI container.
 */
return [
    // Services to add to the container.
    "services" => [
        "flash" => [
            "shared" => true,
            "callback" => function () {
                $flash = new \Faxity\DI\Flash();
                $flash->setDI($this);
                $flash->render();

                return $flash;
            },
        ],
        "weather" => [
            "shared" => true,
            "callback" => function () {
                $cfg = $this->configuration->load("api");
                $accessKey = $cfg["config"]["darksky"];
                $fetch = new \Faxity\Fetch\Fetch();

                $weather = new \Faxity\DI\Weather($accessKey, $fetch);
                $weather->setDI($this);

                return $weather;
            },
        ],
        "ip" => [
            "shared" => true,
            "callback" => function () {
                $cfg = $this->configuration->load("api");
                $accessKey = $cfg["config"]["ipstack"];
                $fetch = new \Faxity\Fetch\Fetch();

                $ip = new \Faxity\DI\IP($accessKey, $fetch);
                $ip->setDI($this);

                return $ip;
            },
        ],
    ],
];
