<?php
namespace Mezon\Gui\Field;

use Mezon\Gui\FieldsAlgorithms;
use Mezon\Gui\FormBuilder\FormBuilder;

//TODO extract to separate package
/**
 * Class RecordField
 *
 * @package Field
 * @subpackage RecordField
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/13)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Record field control
 */
class RecordField extends RemoteField
{

    /**
     * Bind field
     *
     * @var string
     */
    protected $bindField = '';

    /**
     * Layout
     *
     * @var array
     */
    protected $layout = [];

    /**
     * Method fetches bind field property from the description
     *
     * @param array $fieldDescription
     *            Field description
     */
    protected function initBindField(array $fieldDescription)
    {
        if (isset($fieldDescription['bind-field'])) {
            $this->bindField = $fieldDescription['bind-field'];
        } else {
            throw (new \Exception('Bind field is not defined', - 1));
        }
    }

    /**
     * Method fetches layout from the description
     *
     * @param array $fieldDescription
     *            Field description
     */
    protected function initLayout(array $fieldDescription)
    {
        if (isset($fieldDescription['layout'])) {
            $this->layout = $fieldDescription['layout'];
        } else {
            throw (new \Exception('Layout is not defined', - 1));
        }
    }

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

        $this->initBindField($fieldDescription);

        $this->initLayout($fieldDescription);
    }

    /**
     * Getting list of records
     *
     * @return array List of records
     */
    protected function getFields(): array
    {
        // @codeCoverageIgnoreStart
        return $this->getClient()->getRemoteCreationFormFieldsJson();
        // @codeCoverageIgnoreEnd
    }

    /**
     * Generating records feld
     *
     * @return string HTML representation of the records field
     */
    public function html(): string
    {
        // getting fields
        $formFields = new FieldsAlgorithms($this->getFields(), $this->namePrefix);
        $formFields->removeField($this->bindField);

        // getting form
        $formBuilder = new FormBuilder(
            $formFields,
            $this->sessionId,
            $this->namePrefix,
            $this->layout,
            $this->batch);

        // getting HTML
        return $formBuilder->compileFormFields();
    }
}
