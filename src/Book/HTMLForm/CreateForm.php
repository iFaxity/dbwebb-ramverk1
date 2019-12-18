<?php

namespace Faxity\Book\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Faxity\Book\Book;

/**
 * Form to create an item.
 */
class CreateForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "use_fieldset" => false,
            ],
            [
                "title" => [
                    "type"       => "text",
                    "validation" => ["not_empty"],
                ],
                "author" => [
                    "type"       => "text",
                    "validation" => ["not_empty"],
                ],
                "image" => [
                    "type"       => "text",
                    "validation" => ["not_empty"],
                ],
                "submit" => [
                    "type"     => "submit",
                    "value"    => "Add book",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
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
        $book->title = $this->form->value("title");
        $book->author = $this->form->value("author");
        $book->image = $this->form->value("image");
        $book->save();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->response->redirect("library")->send();
    }
}
