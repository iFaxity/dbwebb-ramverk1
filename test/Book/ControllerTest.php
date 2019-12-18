<?php

namespace Faxity\Book;

use Faxity\Test\ControllerTestCase;
use Faxity\DI\DISorcery;
use Anax\DI\DI;
use Faxity\Book\Book;

/**
 * Test Book Controller.
 */
class ControllerTest extends ControllerTestCase
{
    protected $className = Controller::class;

    /** @var Controller $controller */
    protected $controller;


    protected function createDI(): DI
    {
        $di = new DISorcery(TEST_INSTALL_PATH, ANAX_INSTALL_PATH . "/vendor");
        $di->initialize("config/sorcery.php");

        return $di;
    }


    public function setUp(): void
    {
        parent::setUp();

        $this->di->dbqb->connect()->createTable("Book", [
            "id"     => ["INTEGER", "PRIMARY KEY", "NOT NULL" ],
            "title"  => ["TEXT", "NOT NULL" ],
            "author" => ["TEXT", "NOT NULL" ],
            "image"  => ["TEXT", "NOT NULL" ]
        ])->execute();
    }


    public function tearDown(): void
    {
        $this->di->dbqb->connect()->dropTableIfExists("Book")->execute();
    }


    /**
     * Test indexAction method, but with content in the database
     */
    public function testIndexActionGet()
    {
        $res = $this->controller->indexActionGet();
        $this->assertInstanceOf(\Anax\Response\Response::class, $res);

        // Check if the template gets rendered
        $body = $res->getBody();
        $this->assertContains('<p>There are no books in the library yet.</p>', $body);
    }


    /**
     * Test indexAction method, but with content in the database
     */
    public function testIndexActionGetWithContent()
    {
        // Add a book
        $book = new Book();
        $book->setDb($this->di->dbqb);
        $book->title = "Some book";
        $book->author = "Some Author";
        $book->image = "some_url";
        $book->save();

        $res = $this->controller->indexActionGet();
        $this->assertInstanceOf(\Anax\Response\Response::class, $res);

        // Check if the template gets rendered
        $body = $res->getBody();
        $this->assertContains('<div class="books">', $body);
        $this->assertContains('<div class="title">Some book</div>', $body);
        $this->assertContains('<div class="author">Some Author</div>', $body);
    }


    /**
     * Test addAction method
     */
    public function testAddActionGet()
    {
        $res = $this->controller->addAction();
        $this->assertInstanceOf(\Anax\Response\Response::class, $res);

        // Check if the template gets rendered
        $body = $res->getBody();
        $this->assertContains('<h1>Add a new book</h1>', $body);
    }


    /**
     * Test addAction method with POST data
     */
    public function testAddActionPost()
    {
        $this->di->request->setPost("title", "A title");
        $this->di->request->setPost("author", "An Author");
        $this->di->request->setPost("image", "cover_image.jpg");
        $this->di->request->setPost("submit", "Add book");
        $this->di->request->setPost("anax/htmlform-id", "Faxity\Book\HTMLForm\CreateForm");
        $this->di->request->setServer("REQUEST_METHOD", "POST");

        $res = $this->controller->addAction();
        $this->assertInstanceOf(\Anax\Response\Response::class, $res);

        // Check if redirect header is set
        $headers = $this->di->response->getHeaders();
        $url = $this->di->url->create("library");
        $this->assertContains("Location: $url", $headers);

        // Check that the book was added to the table
        $book = new Book();
        $book->setDb($this->di->dbqb);
        $books = $book->findAll();
        $this->assertCount(1, $books);

        $book = $books[0];
        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals($book->title, "A title");
        $this->assertEquals($book->author, "An Author");
        $this->assertEquals($book->image, "cover_image.jpg");
    }


    /**
     * Test deleteAction method
     */
    public function testDeleteActionGet()
    {
        // Add a book
        $book = new Book();
        $book->setDb($this->di->dbqb);
        $book->title = "Some book";
        $book->author = "Some Author";
        $book->image = "some_url";
        $book->save();

        $res = $this->controller->deleteAction();
        $this->assertInstanceOf(\Anax\Response\Response::class, $res);

        // Check if the template gets rendered
        $body = $res->getBody();
        $this->assertContains('<h1>Delete an existing book</h1>', $body);
        $this->assertContains("<option value='1'>Some book (1)</option>", $body);
    }


    /**
     * Test deleteAction method with POST data
     */
    public function testDeleteActionPost()
    {
        // Add a book
        $book = new Book();
        $book->setDb($this->di->dbqb);
        $book->title = "Cool book";
        $book->author = "Cool Author";
        $book->image = "cool_cover.jpeg";
        $book->save();

        $this->di->request->setPost("select", $book->id);
        $this->di->request->setPost("submit", "Delete book");
        $this->di->request->setPost("anax/htmlform-id", "Faxity\Book\HTMLForm\DeleteForm");
        $this->di->request->setServer("REQUEST_METHOD", "POST");

        $res = $this->controller->deleteAction();
        $this->assertInstanceOf(\Anax\Response\Response::class, $res);

        // Check if redirect header is set
        $headers = $this->di->response->getHeaders();
        $url = $this->di->url->create("library");
        $this->assertContains("Location: $url", $headers);

        // Check that the book was removed
        $bookId = $book->id;
        $book = new Book();
        $book->setDb($this->di->dbqb);
        $book = $book->findById($bookId);

        $this->assertNull($book->id);
        $this->assertNull($book->title);
        $this->assertNull($book->author);
        $this->assertNull($book->image);
    }


    /**
     * Test updateAction method
     */
    public function testUpdateActionGet()
    {
        // Add a book
        $book = new Book();
        $book->setDb($this->di->dbqb);
        $book->title = "Interesting book";
        $book->author = "Book Lover";
        $book->image = "interesting_book_cover.jpg";
        $book->save();

        $res = $this->controller->updateAction($book->id);
        $this->assertInstanceOf(\Anax\Response\Response::class, $res);

        // Check if the template gets rendered
        $body = $res->getBody();
        $this->assertContains('<h1>Update book</h1>', $body);
        $this->assertContains("name='id' value='$book->id'", $body);
        $this->assertContains("name='title' value='Interesting book'", $body);
        $this->assertContains("name='author' value='Book Lover'", $body);
        $this->assertContains("name='image' value='interesting_book_cover.jpg'", $body);
    }


    /**
     * Test updateAction method with POST data
     */
    public function testUpdateActionPost()
    {
        // Add a book
        $book = new Book();
        $book->setDb($this->di->dbqb);
        $book->title = "Adventures of a programmer";
        $book->author = "Some Programmer";
        $book->image = "programmer_cover.jpeg";
        $book->save();

        $this->di->request->setPost("id", $book->id);
        $this->di->request->setPost("title", "Updated title");
        $this->di->request->setPost("author", "Updated Author");
        $this->di->request->setPost("image", "updated_cover.png");
        $this->di->request->setPost("submit", "Update book");
        $this->di->request->setPost("anax/htmlform-id", "Faxity\Book\HTMLForm\UpdateForm");
        $this->di->request->setServer("REQUEST_METHOD", "POST");

        $res = $this->controller->updateAction($book->id);
        $this->assertInstanceOf(\Anax\Response\Response::class, $res);

        // Check if redirect header is set
        $headers = $this->di->response->getHeaders();
        $url = $this->di->url->create($this->di->request->getCurrentUrl());
        $this->assertContains("Location: $url", $headers);

        // Check that the book was updated
        $bookId = $book->id;
        $book = new Book();
        $book->setDb($this->di->dbqb);
        $book = $book->findById($bookId);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals($book->title, "Updated title");
        $this->assertEquals($book->author, "Updated Author");
        $this->assertEquals($book->image, "updated_cover.png");
    }
}
