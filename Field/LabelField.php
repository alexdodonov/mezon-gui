<?php
namespace Mezon\Gui\Field;

/**
 * Class LabelField
 *
 * @package Field
 * @subpackage LabelField
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/04)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Form header control
 */
class LabelField extends \Mezon\Gui\Field\TextField
{

    /**
     * Generating input feld
     *
     * @return string HTML representation of the input field
     */
    public function html(): string
    {
        $content = '<div class="form-group col-md-12">';
        $content .= '<label class="control-label">' . $this->text . '</label>';

        return $content . '</div>';
    }

    /**
     * Getting field type
     *
     * @return string Field type
     */
    public function getType(): string
    {
        return 'label';
    }

    /**
     *
     * {@inheritdoc}
     * @see \Mezon\Gui\Control::fillAllRow()
     */
    public function fillAllRow(): bool
    {
        return true;
    }
}
