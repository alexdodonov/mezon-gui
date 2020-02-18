<?php
namespace Mezon\Gui\Field;

/**
 * Class Select
 *
 * @package Field
 * @subpackage Select
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/04)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Text area control
 */
class Select extends \Mezon\Gui\Field
{

    /**
     * Control items
     *
     * @var array
     */
    protected $items = [];

    /**
     * Constructor
     *
     * @param array $fieldDescription
     *            Field description
     * @param string $value
     *            Field value
     */
    public function __construct(array $fieldDescription, string $value = '')
    {
        parent::__construct($fieldDescription, $value);

        $itemsSource = $fieldDescription['items'];

        if (is_string($itemsSource) && function_exists($itemsSource)) {
            // callback function forms a list of items
            $this->items = $itemsSource();
        } else {
            $this->items = $itemsSource;
        }
    }

    /**
     * Generating textarea field
     *
     * @return string HTML representation of the textarea field
     */
    public function html(): string
    {
        $content = '<select class="'.$this->class.'"';
        $content .= $this->required ? ' required="required"' : '';
        $content .= ' type="text" name="' . $this->getNamePrefix() . $this->name .
            ($this->batch ? '[{_creation_form_items_counter}]' : '') . '"';
        $content .= $this->disabled ? ' disabled ' : '';
        $content .= $this->toggler === '' ? '' : 'toggler="' . $this->toggler . '" ';
        $content .= $this->toggler === '' ? '' : 'toggle-value="' . $this->toggleValue . '" ';
        $content .= 'value="' . $this->value . '">';

        foreach ($this->items as $id => $title) {
            $content .= '<option value="' . $id . '">' . $title . '</option>';
        }

        return $content . '</select>';
    }
}
