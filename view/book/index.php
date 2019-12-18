<?php

namespace Anax\View;

/**
 * View to view books in the library.
 */
?>

<h1>Welcome to the Library</h1>
<p>
    Here you can see our list of books and even add some of you own if you like.
    To add a book of your own go to "Library" in the menu and click "Add".
</p>

<?php if (!isset($items) || empty($items)) : ?>
    <p>There are no books in the library yet.</p>

    <p>
        To add a book go to the menu and click "Add" under "Library".
    </p>
<?php else : ?>
    <p>
        Click a book to edit it,
        to remove a book go to the menu and click "Delete" under "Library".
    </p>

    <div class="books">
        <?php foreach ($items as $item) : ?>
            <a class="book" href="<?= url("library/update/{$item->id}") ?>">
                <img src="<?= $item->image ?>" alt="Book cover of <?= $item->title ?>">
                <div class="title"><?= $item->title ?></div>
                <div class="author"><?= $item->author ?></div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
