<?php
namespace Mezon\Gui\WidgetsRegistry;

/**
 * Class BootstrapWidgets
 *
 * @package WidgetsRegistry
 * @subpackage BootstrapWidgets
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/02)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Bootstrap widgets
 */
class BootstrapWidgets implements WidgetsRegistryBase
{

    /**
     * Method returns widget
     *
     * @param string $name
     *            name of the widget
     * @return string widget's HTML code
     */
    public function getWidget(string $name): string
    {
        return BootstrapWidgets::get($name);
    }

    /**
     * Method returns widget
     *
     * @param string $name
     *            name of the widget
     * @return string widget's HTML code
     */
    public static function get(string $name): string
    {
        return file_get_contents(__DIR__ . '/res/templates/' . $name . '.tpl');
    }
}
