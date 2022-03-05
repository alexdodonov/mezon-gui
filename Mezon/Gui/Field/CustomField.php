<?php
namespace Mezon\Gui\Field;

use Mezon\TemplateEngine\TemplateEngine;
use Mezon\Gui\Field;

/**
 * Class CustomField
 *
 * @package Field
 * @subpackage CustomField
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/13)
 * @copyright Copyright (c) 2019, http://aeon.su
 */

/**
 * Custom field control
 */
class CustomField extends Field
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
        $content = file_get_contents('./res/templates/field-' . $this->getName() . '.tpl');

        if ($content === false) {
            throw (new \Exception('Template field-' . $this->getName() . '.tpl was not found'));
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
        return TemplateEngine::printRecord(
            $this->getFieldTemplate(),
            [
                'name' => $this->getName(),
                'name-prefix' => $this->namePrefix,
                'disabled' => $this->disabled ? 1 : 0,
                'batch' => $this->isBatch() ? 1 : 0,
                'custom' => $this->isCustom(),
                'required' => $this->isRequired() ? 1 : 0,
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
