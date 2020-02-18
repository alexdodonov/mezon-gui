<?php
namespace Mezon\Gui;

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
class FieldsAlgorithms
{

    /**
     * List of control objects
     *
     * @var array
     */
    protected $fieldObjects = [];

    /**
     * Entity name
     *
     * @var string
     */
    protected $entityName = false;

    /**
     * Session Id
     *
     * @var string
     */
    protected $sessionId = '';

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
        $this->entityName = $entityName;

        foreach ($fields as $name => $field) {
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
     * Method returns true if the entity has custom fields
     * False otherwise
     *
     * @return bool true if the entity has custom fields
     */
    public function hasCustomFields(): bool
    {
        foreach ($this->fieldObjects as $field) {
            if ($field->getType() == 'custom') {
                return true;
            }
        }

        return false;
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
     * Method validates if the field $field exists
     *
     * @param string $field
     *            Field name
     */
    public function validateFieldExistance(string $field)
    {
        if (! isset($this->fieldObjects[$field])) {
            throw (new \Exception('Field "' . $field . '" was not found'));
        }
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
     * @param string $name
     *            Field name
     */
    public function removeField($name)
    {
        unset($this->fieldObjects[$name]);
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
            if (isset($_POST[$this->entityName . '-' . $name])) {
                $record[$name] = $this->getTypedValue($field['type'], $_POST[$this->entityName . '-' . $name], true);
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
     * @return \Mezon\Gui\Field|\Mezon\Gui\Control constructed control
     */
    protected function constructControl(array $field)
    {
        $typeMap = [
            'external' => \Mezon\Gui\Field\CheckboxesField::class,
            'records' => \Mezon\Gui\Field\RecordField::class,
            'file' => \Mezon\Gui\Field\InputFile::class,
            'date' => \Mezon\Gui\Field\InputDate::class,
            'custom' => \Mezon\Gui\Field\CustomField::class,
            'header' => \Mezon\Gui\FormBuilder\FormHeader::class,
            'label' => \Mezon\Gui\Field\LabelField::class
        ];

        if (isset($field['items'])) {
            return new \Mezon\Gui\Field\Select($field);
        } elseif (isset($field['type'])) {
            if (in_array($field['type'], array_keys($typeMap))) {
                $className = $typeMap[$field['type']];

                $field['session-id'] = $this->sessionId;

                return new $className($field);
            } else {
                return new \Mezon\Gui\Field\InputText($field);
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
            $control = new \Mezon\Gui\Field\Textarea($field);
        } elseif ($field['type'] == 'rows') {
            $control = new \Mezon\Gui\FormBuilder\RowsField($field['type']['rows'], $this->entityName);
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
     * Method returns array of fields names
     *
     * @return array
     */
    public function getFieldsNames(): array
    {
        return array_keys($this->fieldObjects);
    }

    /**
     * Method returns true if the field exists
     *
     * @param string $fieldName
     *            Field name
     * @return bool
     */
    public function hasField(string $fieldName): bool
    {
        // @codeCoverageIgnoreStart
        return isset($this->fieldObjects[$fieldName]);
        // @codeCoverageIgnoreEnd
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
