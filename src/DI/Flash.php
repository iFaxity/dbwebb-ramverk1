<?php

namespace Faxity\DI;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * DI module for creating and rendering flash messages
 */
class Flash implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * @var array $messages Messages buffer
     */
    private $messages = [];


    /**
     * Adds message to buffer
     * @param string $type Message type (ok, error or warning)
     * @param string $text Message text, not required
     */
    private function message(string $type, string $text) : void
    {
        $this->messages[] = (object) [
            "type" => $type,
            "text" => $text,
        ];
    }


    /**
     * Gets all messages within the buffer
     *
     * @return array
     */
    public function getMessages() : array
    {
        return $this->messages;
    }


    /**
     * Renders flash messages into an Anax view
     */
    public function render() : void
    {
        // Pass messages by reference, as all messages are created after this function call
        $data = [ "messages" => &$this->messages ];
        $this->di->view->add("faxity/flash/default", $data, "flash");
    }


    /**
     * Adds an ok message to render
     * @param string $text Message text, not required
     */
    public function ok(string $text) : void
    {
        $this->message("ok", $text);
    }


    /**
     * Adds a warning message to render
     * @param string $text Message text, not required
     */
    public function warn(string $text) : void
    {
        $this->message("warn", $text);
    }


    /**
     * Adds an error message to render
     * @param string $text Message text, not required
     */
    public function err(string $text) : void
    {
        $this->message("err", $text);
    }
}
