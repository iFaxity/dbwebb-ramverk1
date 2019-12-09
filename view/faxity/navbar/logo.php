<?php

namespace Anax\View;

/**
 * Template file to render a view.
 */
?>

<a href="<?= url($homeLink) ?>">
    <?php if (isset($logo)) : ?>
        <span class="site-logo" >
            <img src="<?= asset($logo) ?>" alt="<?= $logoAlt ?>">
        </span>
    <?php endif; ?>

    <?= $logoText ?>
</a>
