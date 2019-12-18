<?php

namespace Faxity\Book;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Book extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Book";


    /**
     * Columns in the table.
     *
     * @var int    $id primary key auto incremented.
     * @var string $title
     * @var string $author
     * @var string $image
     */
    public $id;
    public $title;
    public $author;
    public $image;
}
