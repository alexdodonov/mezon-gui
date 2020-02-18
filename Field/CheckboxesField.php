<?php
namespace Mezon\Gui\Field;

/**
 * Class CheckboxesField
 *
 * @package Field
 * @subpackage CheckboxesField
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/13)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Checkboxes field control
 */
class CheckboxesField extends \Mezon\Gui\Field\RemoteField
{

    /**
     * Getting list of records
     *
     * @return array List of records
     */
    protected function getExternalRecords(): array
    {
        // @codeCoverageIgnoreStart
        return $this->getClient()->getAll();
        // @codeCoverageIgnoreEnd
    }

    /**
     * Method returns record's title
     *
     * @param array $record
     *            Data source
     * @return string Compiled title
     */
    protected function getExternalTitle(array $record): string
    {
        if (\Mezon\Functional\Functional::getField($record, 'title') !== null) {
            return \Mezon\Functional\Functional::getField($record, 'title');
        } else {
            return 'id : ' . \Mezon\Functional\Functional::getField($record, 'id');
        }
    }

    /**
     * Generating records feld
     *
     * @return string HTML representation of the records field
     */
    public function html(): string
    {
        $content = '';

        $records = $this->getExternalRecords();

        foreach ($records as $item) {
            $id = \Mezon\Functional\Functional::getField($item, 'id');

            $content .= '<label>
                <input type="checkbox" class="'.$this->class.'" name="' . $this->getNamePrefix() . $this->name . '[]" value="' .
                $id . '" /> ' . $this->getExternalTitle($item) . '
            </label><br>';
        }

        return $content;
    }
}
