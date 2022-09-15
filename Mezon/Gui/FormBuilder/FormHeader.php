<?php
namespace Mezon\Gui\FormBuilder;

use Mezon\Gui\Field\TextField;

/**
 * Class FormHeader
 *
 * @package Field
 * @subpackage FormHeader
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/04)
 * @copyright Copyright (c) 2019, http://aeon.su
 */

/**
 * Form header control
 */
class FormHeader extends TextField
{

    /**
     * Generating input feld
     *
     * @return string HTML representation of the input field
     */
    public function html(): string
    {
        $content = '<div class="form-group col-md-12">';
        $content .= strlen($this->value) ? '<h3>' . $this->value . '</h3>' : '';

        return $content . '</div>';
    }

    /**
     * Does control fills all row
     */
    public function fillAllRow(): bool
    {
        return false;
    }
}
