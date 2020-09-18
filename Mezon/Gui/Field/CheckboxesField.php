<?php
namespace Mezon\Gui\Field;

use Mezon\Functional\Fetcher;

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
class CheckboxesField extends RemoteField
{
    // TODO unbind dependency - make possible to initialize list of records 
    // - statically
    // - locally

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
        if (Fetcher::getField($record, 'title') !== null) {
            return Fetcher::getField($record, 'title');
        } else {
            return 'id : ' . Fetcher::getField($record, 'id');
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
            $id = Fetcher::getField($item, 'id');

            $content .= '<label>
                <input type="checkbox" class="'.$this->class.'" name="' . $this->getNamePrefix() . $this->name . '[]" value="' .
                $id . '" /> ' . $this->getExternalTitle($item) . '
            </label><br>';
        }

        return $content;
    }
}
