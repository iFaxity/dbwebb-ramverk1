<?php

namespace Faxity\Book\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Faxity\Book\Book;

/**
 * Form to update an item.
 */
class UpdateForm extends FormModel
{
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $book = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "use_fieldset" => false,
            ],
            [
                "id" => [
                    "type"       => "text",
                    "validation" => ["not_empty"],
                    "readonly"   => true,
                    "value"      => $book->id,
                ],
                "title" => [
                    "type"       => "text",
                    "validation" => ["not_empty"],
                    "value"      => $book->title,
                ],
                "author" => [
                    "type"       => "text",
                    "validation" => ["not_empty"],
                    "value"      => $book->author,
                ],
                "image" => [
                    "type"       => "text",
                    "validation" => ["not_empty"],
                    "value"      => $book->image,
                ],
                "submit" => [
                    "type"       => "submit",
                    "value"      => "Save",
                    "callback"   => [$this, "callbackSubmit"]
                ],
                "reset" => [
                    "type"       => "reset",
                ],
            ]
        );
    }



    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return Book
     */
    public function getItemDetails($id) : object
    {
        $book = new Book();
        $book->setDb($this->di->dbqb);
        $book->find("id", $id);
        return $book;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $book = new Book();
        $book->setDb($this->di->dbqb);
        $book->find("id", $this->form->value("id"));
        $book->title = $this->form->value("title");
        $book->author = $this->form->value("author");
        $book->image = $this->form->value("image");
        $book->save();
        return true;
    }
}
