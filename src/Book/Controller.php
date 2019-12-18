<?php

namespace Faxity\Book;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Faxity\Book\HTMLForm\CreateForm;
use Faxity\Book\HTMLForm\DeleteForm;
use Faxity\Book\HTMLForm\UpdateForm;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class Controller implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;


    /**
     * Landing page for the book list
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $book = new Book();
        $book->setDb($this->di->dbqb);

        $this->di->page->add("book/index", [
            "items" => $book->findAll(),
        ]);

        return $this->di->page->render([
            "title" => "Library",
        ]);
    }


    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function addAction() : object
    {
        $form = new CreateForm($this->di);
        $form->check();

        $this->di->page->add("book/add", [
            "form" => $form->getHTML(),
        ]);

        return $this->di->page->render([
            "title" => "Add a new book",
        ]);
    }


    /**
     * Handler with form to delete an item.
     *
     * @return object as a response object
     */
    public function deleteAction() : object
    {
        $form = new DeleteForm($this->di);
        $form->check();

        $this->di->page->add("book/delete", [
            "form" => $form->getHTML(),
        ]);

        return $this->di->page->render([
            "title" => "Delete a book",
        ]);
    }


    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id) : object
    {
        $form = new UpdateForm($this->di, $id);
        $form->check();

        $this->di->page->add("book/update", [
            "form" => $form->getHTML(),
        ]);

        return $this->di->page->render([
            "title" => "Update a book",
        ]);
    }
}
