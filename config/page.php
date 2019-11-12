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
                "js/main.js",
            ],
        ],
    ],

    // These views are always loaded into the collection of views.
    "views" => [
        [
            "region" => "header-logo",
            "template" => "faxity/header/logo",
            "data" => [
                "homeLink" => "",
                "logoText" => "ramverk1",
                "logo"     => "image/theme/leaf_40x40.png",
                "logoAlt"  => "Löv-bild",
            ],
        ],
        [
            "region" => "header",
            "template" => "faxity/navbar/navbar",
            "data" => [
                "navbarConfig" => require __DIR__ . "/navbar/header.php",
            ],
        ],
        [
            "region" => "header-mobile",
            "template" => "faxity/navbar/responsive",
            "data" => [
                "navbarConfig" => require __DIR__ . "/navbar/header.php",
            ],
        ],
        [
            "region" => "footer",
            "template" => "faxity/columns/default",
            "data" => [
                "class"  => "footer-column",
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
            "region" => "footer",
            "template" => "anax/v2/block/default",
            "data" => [
                "class"  => "site-footer",
                "contentRoute" => "block/footer",
            ],
            "sort" => 2,
        ],
    ],
];
