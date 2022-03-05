<?php
namespace Mezon\Gui\Field;

use Mezon\Gui\Field;

/**
 * Class InputDate
 *
 * @package Field
 * @subpackage InputDate
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/13)
 * @copyright Copyright (c) 2019, http://aeon.su
 */

/**
 * Input field control
 */
class InputDate extends Field
{

    /**
     * Generating input feld
     *
     * @return string HTML representation of the input field
     */
    public function html(): string
    {
        return '<input class="' . $this->class . ' date-input""' . ($this->isRequired() ? ' required="required"' : '') .
            ' type="text" name="' . $this->getNamePrefix() . $this->getName() .
            ($this->isBatch() ? '[{_creation_form_items_counter}]' : '') . '"' . ($this->disabled ? ' disabled ' : '') .
            ($this->toggler === '' ? '' : 'toggler="' . $this->toggler . '" ') .
            ($this->toggler === '' ? '' : 'toggle-value="') . $this->toggleValue . '"' . '>';
    }
}
