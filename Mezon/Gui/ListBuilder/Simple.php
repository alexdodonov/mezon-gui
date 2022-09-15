<?php
namespace Mezon\Gui\ListBuilder;

use Mezon\Gui\WidgetsRegistry\BootstrapWidgets;
use Mezon\TemplateEngine\TemplateEngine;
use Mezon\Transport\Request;

/**
 * Class Simple
 *
 * @package GUI
 * @subpackage ListBuilder
 * @author Dodonov A.A.
 * @version v.1.0 (2020/12/19)
 * @copyright Copyright (c) 2020, http://aeon.su
 */

/**
 * Class constructs grids
 */
class Simple extends Base
{

    /**
     * Method compiles listing header
     *
     * @return string Compiled header
     */
    private function simpleListingHeader(): string
    {
        $content = BootstrapWidgets::get('simple-listing-header');

        $content = str_replace([
            '{list-description}',
            '{list-title}'
        ], [
            Request::getParam('list-description', $this->listDescription),
            $this->listTitle
        ], $content);

        return str_replace('{cells}', $this->listingHeaderCells(), $content);
    }

    /**
     * Method compiles listing items
     *
     * @param array $records
     *            List of records
     * @return string Compiled simple list
     */
    private function simpleListingItems(array $records): string
    {
        $content = '';

        foreach ($records as $record) {
            $content .= str_replace('{items}', $this->listingItemsCells(), BootstrapWidgets::get('listing-row'));

            $record = $this->transformRecord($record);

            $record = $this->getListBuilderAdapter()->preprocessListItem($record);

            $content = TemplateEngine::printRecord($content, $record);
        }

        return $content;
    }

    /**
     * Method compiles simple_listing form
     *
     * @return string Compiled simple listing form
     */
    public function listingForm(): string
    {
        $records = $this->getListBuilderAdapter()->all();

        if (! empty($records)) {
            $header = $this->simpleListingHeader();

            $items = $this->simpleListingItems($records);

            // they are the same with full feature listing
            $footer = BootstrapWidgets::get('listing-footer');

            return $header . $items . $footer;
        } else {
            return $this->getNoItemsContent();
        }
    }
}
