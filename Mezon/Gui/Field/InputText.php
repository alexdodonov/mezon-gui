<?php
namespace Mezon\Gui\Field;

use Mezon\Gui\Field;

/**
 * Class InputText
 *
 * @package Field
 * @subpackage InputText
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/04)
 * @copyright Copyright (c) 2019, http://aeon.su
 */

/**
 * Input field control
 */
class InputText extends Field
{

    /**
     * Generating input feld
     *
     * @return string HTML representation of the input field
     */
    public function html(): string
    {
        $content = '<input class="'.$this->class.'"';
        $content .= $this->isRequired() ? ' required="required"' : '';
        $content .= ' type="text" name="' . $this->getNamePrefix() . $this->getName() .
            ($this->isBatch() ? '[{_creation_form_items_counter}]' : '') . '"';
        $content .= $this->disabled ? ' disabled ' : '';
        $content .= $this->toggler === '' ? '' : 'toggler="' . $this->toggler . '" ';
        $content .= $this->toggler === '' ? '' : 'toggle-value="' . $this->toggleValue . '"';

        return $content . '>';
    }
}
