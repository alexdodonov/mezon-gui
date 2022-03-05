<?php
namespace Mezon\Gui\Field;

use Mezon\Gui\Field;

/**
 * Class Textarea
 *
 * @package Field
 * @subpackage Textarea
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/04)
 * @copyright Copyright (c) 2019, http://aeon.su
 */

/**
 * Text area control
 */
class Textarea extends Field
{

    /**
     * Generating textarea field
     *
     * @return string HTML representation of the textarea field
     */
    public function html(): string
    {
        $content = '<textarea class="resizable_textarea '.$this->class.'"';
        $content .= $this->isRequired() ? ' required="required"' : '';
        $content .= ' type="text" name="' . $this->getNamePrefix() . $this->getName() .
            ($this->isBatch() ? '[{_creation_form_items_counter}]' : '') . '"';
        $content .= $this->disabled ? ' disabled ' : '';
        $content .= $this->toggler === '' ? '' : 'toggler="' . $this->toggler . '" ';
        $content .= $this->toggler === '' ? '' : 'toggle-value="' . $this->toggleValue . '"';
        $content .= '>' . $this->value;

        return $content . '</textarea>';
    }
}
