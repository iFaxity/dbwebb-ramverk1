<?php

namespace Anax\View;

/**
 * Template file to render a view.
 */

$navbar = new \Faxity\Navbar\Navbar();
$navbar->setDI($di);

// Render the menu
echo $navbar->createMenuWithSubMenus($navbarConfig);
