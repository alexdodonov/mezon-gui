<?php
namespace Mezon\Gui\Field;

use Mezon\Gui\Field;

/**
 * Class Select
 *
 * @package Field
 * @subpackage Select
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/04)
 * @copyright Copyright (c) 2019, http://aeon.su
 */

/**
 * Text area control
 */
class Select extends Field
{

    /**
     * Control items
     *
     * @var array|callable
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
        $fieldDescription['type'] = isset($fieldDescription['type']) ? $fieldDescription['type'] : 'integer';

        parent::__construct($fieldDescription, $value);

        $this->items = $fieldDescription['items'];
    }

    /**
     * Generating textarea field
     *
     * @return string HTML representation of the textarea field
     */
    public function html(): string
    {
        $content = '<select class="' . $this->class . '"';
        $content .= $this->isRequired() ? ' required="required"' : '';
        $content .= ' type="text" name="' . $this->getNamePrefix() . $this->getName() .
            ($this->isBatch() ? '[{_creation_form_items_counter}]' : '') . '" ';
        $content .= $this->disabled ? ' disabled ' : '';
        $content .= $this->toggler === '' ? '' : 'toggler="' . $this->toggler . '" ';
        $content .= $this->toggler === '' ? '' : 'toggle-value="' . $this->toggleValue . '" ';
        $content .= 'value="' . $this->value . '">';

        $itemSource = $this->items;

        if ((is_string($itemSource) && function_exists($itemSource)) || is_callable($itemSource)) {
            $items = $itemSource();
        } else {
            $items = $itemSource;
        }

        foreach ($items as $id => $title) {
            $content .= '<option value="' . $id . '">' . $title . '</option>';
        }

        return $content . '</select>';
    }
}
