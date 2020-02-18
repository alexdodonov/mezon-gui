<?php
namespace Mezon\Gui\Field;

/**
 * Class CustomField
 *
 * @package Field
 * @subpackage CustomField
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/13)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Custom field control
 */
class CustomField extends \Mezon\Gui\Field
{

    /**
     * Custom field's parts
     *
     * @var array
     */
    protected $fields = [];

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

        $this->fields = $fieldDescription['fields'];
    }

    /**
     * Method returns field's template
     *
     * @return string field's template
     */
    protected function getFieldTemplate(): string
    {
        // @codeCoverageIgnoreStart
        $content = file_get_contents('./res/templates/field-' . $this->name . '.tpl');

        if ($content === false) {
            throw (new \Exception('Template field-' . $this->name . '.tpl was not found'));
        }

        return $content;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Generating custom feld
     *
     * @return string HTML representation of the custom field
     */
    public function html(): string
    {
        return \Mezon\TemplateEngine\TemplateEngine::printRecord(
            $this->get_field_template(),
            [
                'name' => $this->name,
                'name-prefix' => $this->namePrefix,
                'disabled' => $this->disabled ? 1 : 0,
                'batch' => $this->batch ? 1 : 0,
                'custom' => $this->custom,
                'required' => $this->required ? 1 : 0,
                'toggler' => $this->toggler,
                'toggle-value' => $this->toggleValue,
                'class' => $this->class
            ]);
    }

    /**
     * Method returns parts of the custom field
     *
     * @return array parts of the custom field
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
