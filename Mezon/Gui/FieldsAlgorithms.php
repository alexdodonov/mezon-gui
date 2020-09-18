<?php
namespace Mezon\Gui;

use Mezon\Gui\Field\CheckboxesField;
use Mezon\Gui\Field\RecordField;
use Mezon\Gui\Field\InputFile;
use Mezon\Gui\Field\InputDate;
use Mezon\Gui\Field\CustomField;
use Mezon\Gui\FormBuilder\FormHeader;
use Mezon\Gui\Field\LabelField;
use Mezon\Gui\Field\Select;
use Mezon\Gui\Field\InputText;
use Mezon\Gui\Field\Textarea;
use Mezon\Gui\FormBuilder\RowsField;

/**
 * Class FieldsAlgorithms
 *
 * @package Gui
 * @subpackage FieldsAlgorithms
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/08)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class constructs forms
 */
class FieldsAlgorithms extends \Mezon\FieldsSet
{

    /**
     * List of control objects
     *
     * @var array
     */
    private $fieldObjects = [];

    /**
     * Entity name
     *
     * @var string
     */
    private $entityName = false;

    /**
     * Session Id
     *
     * @var string
     */
    private $sessionId = '';

    /**
     * Constructor
     *
     * @param array $fields
     *            List of all fields
     * @param string $entityName
     *            Entity name
     */
    public function __construct(array $fields = [], string $entityName = '')
    {
        parent::__construct($fields);

        $this->entityName = $entityName;

        foreach ($this->getFields() as $name => $field) {
            $field['name'] = $name;
            $field['name-prefix'] = $this->entityName;

            $this->fieldObjects[$name] = $this->initObject($field);
        }
    }

    /**
     * Returning date value
     *
     * @param string $value
     *            Value to be made secure
     * @return string Secure value
     */
    protected function getDateValue(string $value): string
    {
        if ($value == '""') {
            return '';
        } else {
            return date('Y-m-d', strtotime($value));
        }
    }

    /**
     * Returning date value
     *
     * @param array $value
     *            Value to be made secure
     * @return array Secure value
     */
    protected function getExternalValue(array $value): array
    {
        foreach ($value as $i => $item) {
            $value[$i] = intval($item);
        }

        return $value;
    }

    /**
     * Method returns typed value
     *
     * @param string $type
     *            of the field
     * @param string|array $value
     *            of the field
     * @param bool $storeFiles
     *            Need the uploaded file to be stored
     * @return mixed Secured value
     */
    public function getTypedValue(string $type, $value, bool $storeFiles = true)
    {
        $result = '';

        switch ($type) {
            case ('integer'):
                $result = intval($value);
                break;

            case ('string'):
                $result = \Mezon\Security\Security::getStringValue($value);
                break;

            case ('file'):
                $result = \Mezon\Security\Security::getFileValue($value, $storeFiles);
                break;

            case ('date'):
                $result = $this->getDateValue($value);
                break;

            case ('external'):
                $result = $this->getExternalValue($value);
                break;

            default:
                throw (new \Exception('Undefined type "' . $type . '"'));
        }

        return $result;
    }

    /**
     * Getting secure value
     *
     * @param string $field
     *            Field name
     * @param mixed $value
     *            Field value
     * @param bool $storeFiles
     *            Should we store files
     * @return mixed Secure value of the field
     */
    public function getSecureValue(string $field, $value, bool $storeFiles = true)
    {
        $this->validateFieldExistance($field);

        return $this->getTypedValue($this->fieldObjects[$field]->getType(), $value, $storeFiles);
    }

    /**
     * Getting secure values
     *
     * @param string $field
     *            Field name
     * @param mixed $values
     *            Field values
     * @param bool $storeFiles
     *            Should we store files
     * @return mixed Secure values of the field or one value
     */
    public function getSecureValues(string $field, $values, bool $storeFiles = true)
    {
        $return = [];

        if (is_array($values)) {
            foreach ($values as $i => $value) {
                $return[$i] = $this->getSecureValue($field, $value, $storeFiles);
            }
        } else {
            $return = $this->getSecureValue($field, $values, $storeFiles);
        }

        return $return;
    }

    /**
     * Method returns field wich names are started from $prefix
     *
     * @param string $prefix
     *            of the fieldsto be fetched
     * @param bool $storeFiles
     *            Should we store files
     * @return array Fetched fields
     */
    public function getValuesForPrefix(string $prefix, bool $storeFiles = true): array
    {
        $records = [];

        foreach (array_keys($this->fieldObjects) as $name) {
            if (isset($_POST[$prefix . $name])) {
                $records[$name] = $this->getSecureValues($name, $_POST[$prefix . $name], $storeFiles);
            }
        }

        return $records;
    }

    /**
     * Method removes field
     *
     * @param string $fieldName
     *            Field name
     */
    public function removeField($fieldName)
    {
        parent::removeField($fieldName);

        if (isset($this->fieldObjects[$fieldName])) {
            unset($this->fieldObjects[$fieldName]);
        }
    }

    /**
     * Method fetches returns custom fields for saving
     *
     * @param array $record
     *            Record to be extended
     * @param string $name
     *            Name od the field
     * @return array Extended record
     */
    public function fetchCustomField(array &$record, string $name): array
    {
        if (! isset($this->fieldObjects[$name])) {
            return $record;
        }

        $nestedFields = $this->fieldObjects[$name]->getFields();

        foreach ($nestedFields as $name => $field) {
            $fieldName = $this->entityName === '' ? $name : $this->entityName . '-' . $name;

            if (isset($_POST[$fieldName])) {
                $record[$name] = $this->getTypedValue($field['type'], $_POST[$fieldName], true);
            }
        }

        return $record;
    }

    /**
     * Method fetches submitted field
     *
     * @param array $record
     *            Record to be extended
     * @param string $name
     *            Name od the field
     */
    public function fetchField(array &$record, string $name)
    {
        if (isset($_POST[$this->entityName . '-' . $name])) {
            $record[$name] = $this->getSecureValue($name, $_POST[$this->entityName . '-' . $name]);
        } elseif (isset($_FILES[$this->entityName . '-' . $name])) {
            $record[$name] = $this->getSecureValue($name, $_FILES[$this->entityName . '-' . $name]);
        } elseif ($this->hasCustomFields()) {
            $record = $this->fetchCustomField($record, $name);
        }
    }

    /**
     * Factory method for creating controls
     *
     * @param array $field
     *            field description
     * @return Field|Control constructed control
     */
    protected function constructControl(array $field)
    {
        // TODO remove type map and use class names CheckboxesField::class directly
        $typeMap = [
            'external' => CheckboxesField::class,
            'records' => RecordField::class,
            'file' => InputFile::class,
            'date' => InputDate::class,
            'custom' => CustomField::class,
            'header' => FormHeader::class,
            'label' => LabelField::class
        ];

        if (isset($field['items'])) {
            return new Select($field);
        } elseif (isset($field['type'])) {
            if (in_array($field['type'], array_keys($typeMap))) {
                $className = $typeMap[$field['type']];

                $field['session-id'] = $this->sessionId;

                return new $className($field);
            } else {
                return new InputText($field);
            }
        }
    }

    /**
     * Method inits control
     *
     * @param array $field
     *            Field
     * @return mixed Control
     */
    protected function initObject(array $field)
    {
        if (isset($field['control']) && $field['control'] == 'textarea') {
            $control = new Textarea($field);
        } elseif ($field['type'] == 'rows') {
            $control = new RowsField($field['type']['rows'], $this->entityName);
        } else {
            $control = $this->constructControl($field);
        }

        return $control;
    }

    /**
     * Method returns field object
     *
     * @param string $name
     *            Field name
     * @return \Mezon\Gui\Field Field object
     */
    public function getObject(string $name): \Mezon\Gui\Field
    {
        return $this->fieldObjects[$name];
    }

    /**
     * Method compiles field DOM
     *
     * @param string $name
     *            Field name
     */
    public function getCompiledField(string $name)
    {
        $control = $this->getObject($name);

        return $control->html();
    }

    /**
     * Method returns entity name
     *
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * Method sets session id
     *
     * @param string $sessionId
     *            new session id
     */
    public function setSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }
}
