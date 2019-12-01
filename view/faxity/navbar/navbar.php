<?php

namespace Anax\View;

/**
 * Template file to render a view.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

$navbar = new \Faxity\Navbar\Navbar();
$navbar->setDI($di);
$html = $navbar->createMenuWithSubMenus($navbarConfig);

// Render the menu
echo $html;
