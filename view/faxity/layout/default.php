<?php

namespace Anax\View;

use Anax\StyleChooser\StyleChooserController;

/**
 * A layout rendering views in defined regions.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

$htmlClass = $htmlClass ?? [];
$lang = $lang ?? "sv";
$charset = $charset ?? "utf-8";
$title = ($title ?? "No title") . ($baseTitle ?? " | No base title defined");
$bodyClass = $bodyClass ?? null;

// Set active stylesheet
$request = $di->get("request");
$session = $di->get("session");
if ($request->getGet("style")) {
    $session->set("redirect", currentUrl());
    redirect("style/update/" . rawurlencode($_GET["style"]));
}

// Get the active stylesheet, if any.
$activeStyle = $session->get(StyleChooserController::getSessionKey(), null);
if ($activeStyle) {
    $stylesheets = [];
    $stylesheets[] = $activeStyle;
}

// Get hgrid & vgrid
if ($request->hasGet("hgrid")) {
    $htmlClass[] = "hgrid";
}
if ($request->hasGet("vgrid")) {
    $htmlClass[] = "vgrid";
}

// Show regions
if ($request->hasGet("regions")) {
    $htmlClass[] = "regions";
}


// Get current route to make as body class
// If route is not empty string
$route = str_replace("/", "-", $di->get("request")->getRoute());

if ($route) {
    $route = "route-" . $route;
}


?>
<!doctype html>
<html lang="<?= $lang ?>" <?= classList($htmlClass) ?>>
<head>
    <meta charset="<?= $charset ?>">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if (isset($favicon)) : ?>
    <link rel="icon" href="<?= asset($favicon) ?>">
    <?php endif; ?>

    <?php if (isset($stylesheets)) : ?>
        <?php foreach ($stylesheets as $stylesheet) : ?>
            <link rel="stylesheet" type="text/css" href="<?= asset($stylesheet) ?>">
        <?php endforeach; ?>
    <?php endif; ?>

</head>

<body <?= classList($bodyClass, $route) ?>>

<!-- siteheader -->
<?php if (regionHasContent("header") || regionHasContent("header-logo")) : ?>
<header class="region-header" role="banner">
    <!-- header-logo -->
    <?php if (regionHasContent("header-logo")) : ?>
    <div class="region-header-logo">
        <?php renderRegion("header-logo") ?>
    </div>
    <?php endif; ?>

    <!-- header -->
    <?php if (regionHasContent("header")) : ?>
    <nav class="region-header-nav">
        <?php renderRegion("header") ?>
    </nav>
    <?php endif; ?>

    <!-- header-mobile -->
    <?php if (regionHasContent("header-mobile")) : ?>
    <nav class="region-header-mobile">
        <?php renderRegion("header-mobile") ?>
    </nav>
    <?php endif; ?>
</header>
<?php endif; ?>


<!-- flash -->
<?php if (regionHasContent("flash")) : ?>
<section class="region-flash">
    <?php renderRegion("flash") ?>
</section>
<?php endif; ?>



<!-- breadcrumb -->
<?php if (regionHasContent("breadcrumb")) : ?>
<section class="region-breadcrumb">
    <?php renderRegion("breadcrumb") ?>
</section>
<?php endif; ?>



<?php
$sidebarLeft  = regionHasContent("sidebar-left");
$sidebarRight = regionHasContent("sidebar-right");
$class = [];

if ($sidebarLeft) {
    $class[] = "has-sidebar-left";
}

if ($sidebarRight) {
    $class[] = "has-sidebar-right";
}
?>

<!-- main -->
<main <?= classList($class) ?>>
    <?php if ($sidebarLeft) : ?>
    <aside class="region-sidebar region-sidebar-left" role="complementary">
        <div class="wrapper">
            <?php renderRegion("sidebar-left") ?>
        </div>
    </aside>
    <?php endif; ?>

    <?php if (regionHasContent("main")) : ?>
    <section class="region-main" role="main">
        <?php renderRegion("main") ?>
    </section>
    <?php endif; ?>

    <?php if ($sidebarRight) : ?>
    <aside class="region-sidebar region-sidebar-right" role="complementary">
        <div class="wrapper">
            <?php renderRegion("sidebar-right") ?>
        </div>
    </aside>
    <?php endif; ?>
</main>



<!-- footer -->
<?php if (regionHasContent("footer")) : ?>
<footer class="region-footer">
    <?php renderRegion("footer") ?>
</footer>
<?php endif; ?>



<!-- render javascripts -->
<?php if (isset($javascripts)) : ?>
    <?php foreach ($javascripts as $javascript) : ?>
    <script src="<?= asset($javascript) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<!--
Inline script with one space stops chrome from triggering transitions on page load
https://lab.laukstein.com/bug/input
https://bugs.chromium.org/p/chromium/issues/detail?id=332189#c20
-->
<script> </script>
</body>
</html>
