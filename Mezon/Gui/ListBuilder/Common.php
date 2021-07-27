<?php
namespace Mezon\Gui\ListBuilder;

use Mezon\Functional\Fetcher;
use Mezon\Gui\WidgetsRegistry\BootstrapWidgets;
use Mezon\TemplateEngine\TemplateEngine;
use Mezon\Transport\Request;

/**
 * Class ListBuilder
 *
 * @package CrudService
 * @subpackage ListBuilder
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/12)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class constructs grids
 */
class Common extends Base
{

    /**
     * Custom actions for each record
     *
     * @var string
     */
    private $customActions = null;

    /**
     * Custom header actions
     *
     * @var string
     */
    private $customHeaderActions = '';

    /**
     * Name of the template for the empty records list
     *
     * @var string
     */
    protected $noItemsTemplateName = 'listing-no-items-with-buttons';

    /**
     * Endpoint for create button
     *
     * @var string
     */
    public $createButtonEndpoint = '';

    /**
     * Method sets custom actions
     *
     * @param string $actions
     */
    public function setCustomActions(string $actions): void
    {
        $this->customActions = $actions;
    }

    /**
     * Method sets custom header actions
     *
     * @param string $actions
     */
    public function setCustomHeaderActions(string $actions): void
    {
        $this->customHeaderActions = $actions;
    }

    /**
     * Method returns end point for the create page form
     *
     * @return string Create page endpoint
     */
    private function getCreatePageEndpoint(): string
    {
        if ($this->createButtonEndpoint !== '') {
            return $this->createButtonEndpoint;
        } elseif (isset($_GET['create-page-endpoint'])) {
            return $_GET['create-page-endpoint'];
        }

        return '../create/';
    }

    /**
     * Method shows "no records" message instead of listing
     *
     * @return string Compiled list view
     */
    private function listingNoItems(): string
    {
        $content = $this->getNoItemsContent();

        if (isset($_GET['create-button']) || $this->createButtonEndpoint !== '') {
            $content = str_replace('{buttons}', BootstrapWidgets::get('listing-header-buttons'), $content);
            $content = str_replace('{create-page-endpoint}', $this->getCreatePageEndpoint(), $content);
        }

        return $content;
    }

    /**
     * Method displays list of possible buttons
     *
     * @param int $id
     *            Id of the record
     * @return string Compiled list buttons
     */
    private function listOfButtons(int $id): string
    {
        return str_replace('{id}', $id, BootstrapWidgets::get('list-of-buttons'));
    }

    /**
     * Need to display actions in list
     *
     * @return bool Do we need add actions
     */
    private function needActions(): bool
    {
        if (@$_GET['update-button'] == 1 || @$_GET['delete-button'] == 1 || $this->customActions !== null) {
            return true;
        }

        return false;
    }

    /**
     * Method compiles listing items cells
     *
     * @param array|object $record
     *            record data
     * @return string Compiled row
     */
    private function listingItemsCells($record): string
    {
        $content = '';

        foreach (array_keys($this->getFields()) as $name) {
            if ($name == 'domain_id') {
                continue;
            }
            if ($name == 'id') {
                $content .= BootstrapWidgets::get('listing-row-centered-cell');
            } else {
                $content .= BootstrapWidgets::get('listing-row-cell');
            }
            $content = str_replace('{name}', '{' . $name . '}', $content);
        }

        if ($this->needActions()) {
            $content .= BootstrapWidgets::get('listing-actions');

            $content = str_replace(
                '{actions}',
                $this->customActions === null ? $this->listOfButtons(Fetcher::getField($record, 'id')) : $this->customActions,
                $content);
        }

        return $content;
    }

    /**
     * Method compiles listing items
     *
     * @param array $records
     *            Listof records
     * @return string Compiled list items
     */
    private function listingItems(array $records): string
    {
        $content = '';

        foreach ($records as $record) {
            $content .= BootstrapWidgets::get('listing-row');
            $content = str_replace('{items}', $this->listingItemsCells($record), $content);

            $record = $this->transformRecord($record);

            $record = $this->getListBuilderAdapter()->preprocessListItem($record);

            $content = TemplateEngine::printRecord($content, $record);
        }

        return $content;
    }

    /**
     * Method compiles header cells
     *
     * @return string Compiled header
     */
    private function listingHeaderCells(): string
    {
        $content = '';

        foreach ($this->getFields() as $name => $data) {
            if ($name == 'domain_id') {
                continue;
            }

            $idStyle = $name == 'id' ? 'style="text-align: center; width:5%;"' : '';

            $content .= BootstrapWidgets::get('listing-header-cell');
            $content = str_replace([
                '{id-style}',
                '{title}'
            ], [
                $idStyle,
                $data['title']
            ], $content);
        }

        if ($this->needActions()) {
            $content .= BootstrapWidgets::get('listing-header-actions');
        }

        return $content;
    }

    /**
     * Method returns listing header content
     *
     * @param
     *            string Compiled header
     */
    private function listingHeaderContent(): string
    {
        if (@$_GET['create-button'] === 1 || $this->createButtonEndpoint !== '') {
            $content = BootstrapWidgets::get('listing-header');

            $content = str_replace('{create-page-endpoint}', $this->getCreatePageEndpoint(), $content);
        } else {
            $content = BootstrapWidgets::get('simple-listing-header');
        }

        return str_replace('{header-actions}', $this->customHeaderActions, $content);
    }

    /**
     * Method compiles listing header
     *
     * @return string Compiled header
     */
    private function listingHeader(): string
    {
        $content = $this->listingHeaderContent();

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
     * Method compiles listing form
     *
     * @return string Compiled listing form
     */
    public function listingForm(): string
    {
        $records = $this->getListBuilderAdapter()->getRecords([
            'field' => 'id',
            'order' => 'ASC'
        ], isset($_GET['from']) ? $_GET['from'] : 0, isset($_GET['limit']) ? $_GET['limit'] : 100);

        if (! empty($records)) {
            $header = $this->listingHeader();

            $items = $this->listingItems($records);

            $footer = BootstrapWidgets::get('listing-footer');

            return $header . $items . $footer;
        } else {
            return $this->listingNoItems();
        }
    }
}
