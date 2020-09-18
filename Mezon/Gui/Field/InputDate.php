<?php
namespace Mezon\Gui\Field;

/**
 * Class InputDate
 *
 * @package Field
 * @subpackage InputDate
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/13)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Input field control
 */
class InputDate extends \Mezon\Gui\Field
{

    /**
     * Generating input feld
     *
     * @return string HTML representation of the input field
     */
    public function html(): string
    {
        return '<input class="' . $this->class . ' date-input""' . ($this->required ? ' required="required"' : '') .
            ' type="text" name="' . $this->getNamePrefix() . $this->name .
            ($this->batch ? '[{_creation_form_items_counter}]' : '') . '"' . ($this->disabled ? ' disabled ' : '') .
            ($this->toggler === '' ? '' : 'toggler="' . $this->toggler . '" ') .
            ($this->toggler === '' ? '' : 'toggle-value="') . $this->toggleValue . '"' . '>';
    }
}
