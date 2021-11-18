<?php
namespace Mezon\Gui\FormBuilder;

use Mezon\Gui\FieldsAlgorithms;

/**
 * Class FormBuilder
 *
 * @package Gui
 * @subpackage FormBuilder
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/13)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Form builder class
 */
class FormBuilder
{

    /**
     * Fields algorithms
     */
    private $fieldsAlgorithms = false;

    /**
     * Session id
     */
    private $sessionId = false;

    /**
     * Entity name
     */
    private $entityName = false;

    /**
     * Layout
     * 
     * @var array
     */
    private $layout = [];

    /**
     * Multiple forms
     */
    private $batch = false;

    /**
     * Constructor
     *
     * @param FieldsAlgorithms $fieldsAlgorithms
     *            fields algorithms
     * @param string $sessionId
     *            session id
     * @param string $entityName
     *            entity name
     * @param array $layout
     *            fields layout
     * @param bool $batch
     *            batch operations available
     */
    public function __construct(
        FieldsAlgorithms $fieldsAlgorithms,
        string $sessionId = '',
        string $entityName = 'default',
        array $layout = [],
        bool $batch = false)
    {
        $this->fieldsAlgorithms = $fieldsAlgorithms;

        $this->sessionId = $sessionId;

        $this->entityName = $entityName;

        $this->layout = $layout;

        $this->batch = $batch;
    }

    /**
     * Method compiles form without layout
     *
     * @return string compiled control
     */
    protected function compileForFieldsWithNoLayout(): string
    {
        $content = '';

        foreach ($this->fieldsAlgorithms->getFieldsNames() as $name) {
            $field = $this->fieldsAlgorithms->getObject($name);
            if ($name == 'id' || $name == 'domain_id' || $name == 'creation_date' || $name == 'modification_date' ||
                $field->isVisible() === false) {
                continue;
            }

            $content .= '<div class="form-group ' . $this->entityName . '">' . '<label class="control-label" >' .
                $field->getTitle() . ($field->isRequired($name) ? ' <span class="required">*</span>' : '') . '</label>' .
                $field->html() . '</div>';
        }

        return $content;
    }

    /**
     * Method compiles atoic field
     *
     * @param array $field
     *            field description
     * @param string $name
     *            HTML field name
     * @return string Compiled field
     */
    protected function compileField($field, $name)
    {
        $control = $this->fieldsAlgorithms->getCompiledField($name);

        $fieldObject = $this->fieldsAlgorithms->getObject($name);

        if ($fieldObject->fillAllRow()) {
            return $control->html();
        }

        if ($fieldObject->isVisible() === false) {
            return '';
        }

        $content = '<div class="form-group ' . $this->entityName . ' col-md-' . $field['width'] . '">';

        if ($fieldObject->hasLabel()) {
            $content .= '<label class="control-label" style="text-align: left;">' . $fieldObject->getTitle() .
                ($fieldObject->isRequired($name) ? ' <span class="required">*</span>' : '') . '</label>';
        }

        return $content . $control . '</div>';
    }

    /**
     * Method compiles form with layout
     *
     * @param array $record
     *            record
     * @return string compiled fields
     */
    protected function compileForFieldsWithLayout(array $record = []): string
    {
        $content = '';

        foreach ($this->layout['rows'] as $row) {
            foreach ($row as $name => $field) {
                $content .= $this->compileField($field, $name, $record);
            }
        }

        return $content;
    }

    /**
     * Method returns amount of columns in the form
     *
     * @return string|int width of the column
     */
    protected function getFormWidth()
    {
        if (isset($_GET['form-width'])) {
            return intval($_GET['form-width']);
        } elseif (empty($this->layout)) {
            return 6;
        } else {
            return $this->layout['width'];
        }
    }

    /**
     * Method compiles form fields
     *
     * @param array $record
     *            record
     * @return string compiled fields
     */
    public function compileFormFields($record = [])
    {
        if (empty($this->layout)) {
            return $this->compileForFieldsWithNoLayout($record);
        } else {
            return $this->compileForFieldsWithLayout($record);
        }
    }

    /**
     * Method compiles creation form
     *
     * @return string compiled creation form
     */
    public function creationForm(): string
    {
        if (isset($_GET['no-header'])) {
            $content = file_get_contents(__DIR__ . '/res/templates/creation_form_no_header.tpl');
        } else {
            $content = file_get_contents(__DIR__ . '/res/templates/creation_form_header.tpl');
        }

        $content .= file_get_contents(__DIR__ . '/res/templates/creation_form.tpl');

        $backLink = isset($_GET['back-link']) ? $_GET['back-link'] : '../list/';

        $content = str_replace('{fields}', $this->compileFormFields(), $content);

        $content = str_replace('{width}', $this->getFormWidth(), $content);

        return str_replace('{back-link}', $backLink, $content);
    }

    /**
     * Method compiles updating form
     *
     * @param string $sessionId
     *            session id
     * @param array $record
     *            record to be updated
     * @return string compiled updating form
     */
    public function updatingForm(string $sessionId, array $record): string
    {
        if (isset($_GET['no-header'])) {
            $content = file_get_contents(__DIR__ . '/res/templates/updating_form_no_header.tpl');
        } else {
            $content = file_get_contents(__DIR__ . '/res/templates/updating_form_header.tpl');
        }

        $content .= file_get_contents(__DIR__ . '/res/templates/updating_form.tpl');

        $this->sessionId = $sessionId;
        $this->fieldsAlgorithms->setSessionId($sessionId);

        return str_replace('{fields}', $this->compileFormFields($record), $content);
    }
}
