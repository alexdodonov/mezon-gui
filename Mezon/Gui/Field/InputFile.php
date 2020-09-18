<?php
namespace Mezon\Gui\Field;

/**
 * Class InputFile
 *
 * @package Field
 * @subpackage InputFile
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/04)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Input field control
 */
class InputFile extends \Mezon\Gui\Field
{

    /**
     * Generating input feld
     *
     * @return string HTML representation of the input field
     */
    public function html(): string
    {
        $content = '<input class="' . $this->class . '"';
        $content .= $this->required ? ' required="required"' : '';
        $content .= ' type="file" name="' . $this->getNamePrefix() . $this->name .
            ($this->batch ? '[{_creation_form_items_counter}]' : '') . '"';
        $content .= $this->disabled ? ' disabled ' : '';
        $content .= $this->toggler === '' ? '' : 'toggler="' . $this->toggler . '" ';
        $content .= $this->toggler === '' ? '' : 'toggle-value="' . $this->toggleValue . '"';

        return $content . '>';
    }
}
