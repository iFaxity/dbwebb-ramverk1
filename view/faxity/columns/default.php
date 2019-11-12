<?php

namespace Anax\View;

/**
 * Render a multiple amount of columns and render a complete view as the
 * content of each column.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());
?>

<div class="columns">
    <?php foreach ($columns as $column) :
        $template = isset($column["template"])
            ? $column["template"]
            : __DIR__ . "/../block/default";
        ?>

    <div class="column">
        <?php
        $data = isset($column["data"]) ? $column["data"] : $column;
        renderView($template, $data);
        ?>
    </div>

    <?php endforeach; ?>
</div>
