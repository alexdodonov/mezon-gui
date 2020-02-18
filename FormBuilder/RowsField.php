<?php
namespace Mezon\Gui\FormBuilder;

/**
 * Class RowsField
 *
 * @package FormBuidler
 * @subpackage RowsField
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/22)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Rows field control
 */
class RowsField extends \Mezon\Gui\Field
{

    /**
     * Rowed field content
     *
     * @var string
     */
    protected $rowedField = '';

    /**
     * Constructor
     *
     * @param array $rows
     *            controls for row
     * @param string $entityName
     *            name of the entity
     */
    public function __construct(array $rows, string $entityName)
    {
        $rowedField = '';

        $FieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($rows, $entityName);

        foreach (array_keys($rows) as $fieldName) {
            $control = $FieldsAlgorithms->getObject($fieldName);
            $rowedField .= $control->html();
        }

        $this->rowedField = $rowedField;
    }

    /**
     * Generating input feld
     *
     * @return string HTML representation of the input field
     */
    public function html(): string
    {
        $content = '<div><div class="form-group col-md-12">';
        $content .= '<button class="btn btn-success col-md-2" onclick="add_element_by_template( this , \'' . $this->name .
            '\' )">+</button>';
        $content .= '</div></div>';

        $content = str_replace('{_creation_form_items_counter}', '0', $content);

        $content .= '<template class="' . $this->name . '"><div>';
        $content .= $this->rowedField;
        $content .= '<div class="form-group col-md-12">';
        $content .= '<button class="btn btn-success col-md-2" onclick="add_element_by_template( this , \'' . $this->name .
            '\' );">+</button>';
        $content .= '<button class="btn btn-danger col-md-2" onclick="remove_element_by_template( this );">-</button>';
        $content .= '</div></div>';

        return $content . '</template>';
    }

    /**
     * Does control fills all row
     */
    public function fillAllRow(): bool
    {
        return true;
    }
}
