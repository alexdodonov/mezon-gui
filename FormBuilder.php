<?php
namespace Mezon\Gui;

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
    protected $fieldsAlgorithms = false;

    /**
     * Session id
     */
    protected $sessionId = false;

    /**
     * Entity name
     */
    protected $entityName = false;

    /**
     * Layout
     */
    protected $layout = false;

    /**
     * Multiple forms
     */
    protected $batch = false;

    /**
     * Constructor
     *
     * @param \Mezon\Gui\FieldsAlgorithms $fieldsAlgorithms
     *            Fields algorithms
     * @param string $sessionId
     *            Session id
     * @param string $entityName
     *            Entity name
     * @param array $layout
     *            Fields layout
     * @param bool $batch
     *            Batch operations available
     */
    public function __construct(
        \Mezon\Gui\FieldsAlgorithms $fieldsAlgorithms,
        string $sessionId,
        string $entityName,
        array $layout,
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
     * @return string Compiled control
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
     *            Field description
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
     *            Record
     * @return string Compiled fields
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
     * @return string|int Width of the column
     */
    protected function getFormWidth()
    {
        if (isset($_GET['form-width'])) {
            return intval($_GET['form-width']);
        } elseif ($this->layout === false || count($this->layout) === 0) {
            return 6;
        } else {
            return $this->layout['width'];
        }
    }

    /**
     * Method compiles form fields
     *
     * @param array $record
     *            Record
     * @return string Compiled fields
     */
    public function compileFormFields($record = [])
    {
        if (count($this->layout) === 0) {
            return $this->compileForFieldsWithNoLayout($record);
        } else {
            return $this->compileForFieldsWithLayout($record);
        }
    }

    /**
     * Method compiles creation form
     *
     * @return string Compiled creation form
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
     *            Session id
     * @param array $record
     *            Record to be updated
     * @return string Compiled updating form
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
