<?php

namespace Faxity\Navbar;

use Anax\Commons\ContainerInjectableTrait;

/**
 * Helper to create a navbar for sites by reading its configuration from file
 * and then applying some code while rendering the resultning navbar.
 *
 * This is a version of anax navigation, but modified so it fits my use instead.
 */
class Navbar
{
    use ContainerInjectableTrait;


    /**
     * Create an url.
     *
     * @param: string $url where to create the url.
     *
     * @return string as the url create.
     */
    public function url($url)
    {
        return $this->di->url->create($url);
    }


    /**
     * Callback tracing the current selected menu item base on scriptname.
     *
     * @param: string $url to check for.
     *
     * @return bool true if item is selected, else false.
     */
    public function check($url)
    {
        return $url == $this->di->request->getRoute();
    }


    /**
     * Create a navigation bar/menu, with submenus.
     *
     * @param array $config with configuration for the menu.
     *
     * @return string with html for the menu.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function createMenuWithSubMenus($config)
    {
        $default = [
            "id"      => null,
            "class"   => null,
            "wrapper" => "nav",
        ];
        $menu = array_replace_recursive($default, $config);

        // Call the anonomous function to create the menu, and submenues if any.
        $class = isset($menu["class"])
            ? " class=\"{$menu["class"]}\""
            : null;

        list($html) = $this->createMenu($menu["items"], $class);

        return "\n{$html}\n";
    }


    /**
     * Create a navigation bar/menu, with submenus.
     *
     * @param array $config with configuration for the menu.
     *
     * @return string with html for the menu.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function createMenu($items, $ulClass = null)
    {
        $html = null;
        $hasItemIsSelected = false;
        $subMenuClass = " class=\"submenu\"";

        foreach ($items as $item) {
            // has submenu, call recursivly and keep track on if the submenu has a selected item in it.
            $subMenu        = null;
            $selectedParent = null;
            $subMenuIcon    = null;
            $selected = $this->check($item["url"]);
            $classes = [];
            $class = "";

            if (isset($item["submenu"])) {
                list($subMenu, $selectedParent) = $this->createMenu($item["submenu"], null, $subMenuClass);
                $subMenuIcon = "<span class=\"submenu-icon\"></span>";

                if ($subMenu) {
                    $classes[] = "has-submenu";
                }

                if ($selectedParent) {
                    $classes[] = "selected-parent";
                }
            }

            if ($selected) {
                $classes[] = "selected";
            }

            if (!empty($classes)) {
                $class = implode(" ", $classes);
                $class = " class=\"{$class}\" ";
            }

            // Add the menu item
            $url = $this->url($item["url"]);
            $html .= "\n<li{$class}><a href='{$url}' title='{$item['title']}'>{$item['text']}</a>{$subMenuIcon}{$subMenu}</li>\n";

            // To remember there is selected children when going up the menu hierarchy
            if ($selected) {
                $hasItemIsSelected = true;
            }
        }

        // Return the menu
        return ["\n<ul$ulClass>$html</ul>\n", $hasItemIsSelected];
    }
}
