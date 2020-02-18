<?php
namespace Mezon\Gui;

/**
 * Interface Control
 *
 * @package Gui
 * @subpackage Control
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/14)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Base interface for all controls
 */
interface Control
{

    /**
     * Control compilation function
     *
     * @return string Compiled control
     */
    public function html(): string;

    /**
     * Does control fills all row
     */
    public function fillAllRow(): bool;
}
