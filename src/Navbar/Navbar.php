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
     */
    public function createMenuWithSubMenus(array $config)
    {
        $default = [
            "id"      => null,
            "class"   => null,
            "wrapper" => "nav",
        ];
        $menu = array_replace_recursive($default, $config);
        $class = isset($menu["class"]) ? " class=\"{$menu["class"]}\"" : null;

        // Call the anonomous function to create the menu, and submenues if any.
        list($html) = $this->createMenu($menu["items"], $class);

        return "\n{$html}\n";
    }


    /**
     * Create a navigation bar/menu.
     *
     * @param array $items Menu items.
     * @param string|null $ulClass Optiona class to append on it's ul element.
     *
     * @return array with html for the menu.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function createMenu(array $items, ?string $ulClass = null)
    {
        $html = null;
        $hasItemIsSelected = false;
        $subMenuClass = " class=\"submenu\"";

        foreach ($items as $item) {
            // has submenu, call recursivly and keep track on if the submenu has a selected item in it.
            $subMenu        = "";
            $selectedParent = "";
            $subMenuIcon    = "";
            $selected = $this->check($item["url"]);
            $classes = [];
            $class = "";

            if (isset($item["submenu"])) {
                list($subMenu, $selectedParent) = $this->createMenu($item["submenu"], $subMenuClass);
                $subMenuIcon = "<span class=\"submenu-icon\"></span>";

                $subMenu && $classes[] = "has-submenu";
                $selectedParent && $classes[] = "selected-parent";
            }

            if ($selected) {
                // Remember there is selected children when going up the menu hierarchy
                $hasItemIsSelected = true;
                $classes[] = "selected";
            }

            if (!empty($classes)) {
                $class = " class=\"" . implode(" ", $classes) ."\" ";
            }

            // Add the menu item
            $url = $this->di->url->create($item["url"]);
            $html .= "\n<li{$class}><a href=\"{$url}\" title=\"{$item['title']}\">{$item['text']}</a>{$subMenuIcon}{$subMenu}</li>\n";
        }

        // Return the menu
        return ["\n<ul$ulClass>$html</ul>\n", $hasItemIsSelected];
    }
}
