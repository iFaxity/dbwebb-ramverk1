<?php
/**
 * Configuration file for page which can create and put together web pages
 * from a collection of views. Through configuration you can add the
 * standard parts of the page, such as header, navbar, footer, stylesheets,
 * javascripts and more.
 */

return [
    "layout" => [
        "region" => "layout",
        // Change here to use your own templatefile as layout
        "template" => "faxity/layout/default",
        "data" => [
            "baseTitle" => " | ramverk1",
            "bodyClass" => null,
            "favicon" => "favicon.ico",
            "htmlClass" => null,
            "lang" => "sv",
            "stylesheets" => [
                "css/theme.min.css",
            ],
            "javascripts" => [
                "js/navbar.js",
            ],
        ],
    ],

    // These views are always loaded into the collection of views.
    "views" => [
        [
            "region"   => "header-logo",
            "template" => "faxity/navbar/logo",
            "data" => [
                "homeLink" => "",
                "logoText" => "ramverk1",
            ],
        ],
        [
            "region"   => "header",
            "template" => "faxity/navbar/header",
            "data" => [
                "navbarConfig" => require __DIR__ . "/navbar.php",
            ],
        ],
        [
            "region"   => "header-mobile",
            "template" => "faxity/navbar/responsive",
            "data" => [
                "navbarConfig" => require __DIR__ . "/navbar.php",
            ],
        ],
        [
            "region"   => "footer",
            "template" => "faxity/columns/default",
            "data" => [
                "class"   => "footer-column",
                "columns" => [
                    [
                        "template" => "anax/v2/block/default",
                        "contentRoute" => "block/footer-col-1",
                    ],
                    [
                        "template" => "anax/v2/block/default",
                        "contentRoute" => "block/footer-col-2",
                    ],
                ],
            ],
            "sort" => 1,
        ],
        [
            "region"   => "footer",
            "template" => "anax/v2/block/default",
            "data" => [
                "class"        => "site-footer",
                "contentRoute" => "block/footer",
            ],
            "sort" => 2,
        ],
    ],
];
