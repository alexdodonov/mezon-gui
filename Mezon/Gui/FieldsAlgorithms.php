<?php
namespace Mezon\Gui;

use Mezon\Gui\Field\InputFile;
use Mezon\Gui\Field\InputDate;
use Mezon\Gui\Field\CustomField;
use Mezon\Gui\FormBuilder\FormHeader;
use Mezon\Gui\Field\LabelField;
use Mezon\Gui\Field\Select;
use Mezon\Gui\Field\InputText;
use Mezon\Gui\Field\Textarea;
use Mezon\Gui\FormBuilder\RowsField;
use Mezon\Security\Security;
use Mezon\FieldsSet;

/**
 * Class FieldsAlgorithms
 *
 * @package Gui
 * @subpackage FieldsAlgorithms
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/08)
 * @copyright Copyright (c) 2019, http://aeon.su
 */

/**
 * Class constructs forms
 */
class FieldsAlgorithms extends FieldsSet
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
     * Supported types
     *
     * @var array
     */
    public static $typeMap = [
        'file' => InputFile::class,
        'date' => InputDate::class,
        'custom' => CustomField::class,
        'header' => FormHeader::class,
        'label' => LabelField::class
    ];

    /**
     * Constructor
     *
     * @param array $fields
     *            list of all fields
     * @param string $entityName
     *            entity name
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
     *            value to be made secure
     * @return string secure value
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
     *            value to be made secure
     * @return array secure value
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
     *            need the uploaded file to be stored
     * @return mixed secured value
     */
    public function getTypedValue(string $type, $value, bool $storeFiles = true)
    {
        $result = '';

        switch ($type) {
            case ('integer'):
                $result = intval($value);
                break;

            case ('string'):
                $result = Security::getStringValue($value);
                break;

            case ('file'):
                $result = Security::getFileValue($value, $storeFiles);
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
     *            field name
     * @param mixed $value
     *            field value
     * @param bool $storeFiles
     *            should we store files
     * @return mixed secure value of the field
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
     *            field name
     * @param mixed $values
     *            field values
     * @param bool $storeFiles
     *            should we store files
     * @return mixed secure values of the field or one value
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
     *            of the fields to be fetched
     * @param bool $storeFiles
     *            should we store files
     * @return array fetched fields
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
     *            field name
     */
    public function removeField($fieldName): void
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
     *            record to be extended
     * @param string $name
     *            name od the field
     * @return array extended record
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
     *            record to be extended
     * @param string $name
     *            name od the field
     */
    public function fetchField(array &$record, string $name):void
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
        if (isset($field['items'])) {
            return new Select($field);
        } elseif (isset($field['type'])) {
            if (in_array($field['type'], array_keys(self::$typeMap))) {
                $className = self::$typeMap[$field['type']];

                $field['session-id'] = $this->sessionId;

                /** @var Control|Field $control */
                $control = new $className($field);

                return $control;
            } else {
                return new InputText($field);
            }
        } else {
            throw (new \Exception('Can not define control\'s type', - 1));
        }
    }

    /**
     * Method inits control
     *
     * @param array $field
     *            field
     * @return mixed control
     */
    protected function initObject(array $field)
    {
        if (isset($field['control']) && $field['control'] == 'textarea') {
            $control = new Textarea($field);
        } elseif (isset($field['type']) && $field['type'] == 'rows') {
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
     *            field name
     * @return Field field object
     */
    public function getObject(string $name): Field
    {
        return $this->fieldObjects[$name];
    }

    /**
     * Method compiles field DOM
     *
     * @param string $name
     *            field name
     * @return string compiled HTML
     */
    public function getCompiledField(string $name): string
    {
        $control = $this->getObject($name);

        return $control->html();
    }

    /**
     * Method returns entity name
     *
     * @return string entity name
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
