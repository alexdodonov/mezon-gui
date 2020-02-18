<?php
namespace Mezon\Gui\WidgetsRegistry;

/**
 * Interface WidgetsRegistryBase
 *
 * @package WidgetsRegistry
 * @subpackage WidgetsRegistryBase
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/02)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class for getting access to widgets
 */
interface WidgetsRegistryBase
{

    /**
     * Method returns widget
     *
     * @param string $name
     *            Name of the widget
     * @return string Widget's HTML code
     */
    public function getWidget(string $name): string;
}
