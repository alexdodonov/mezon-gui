<?php
namespace Mezon\Gui\ListBuilder;

use Mezon\Functional\Fetcher;
use Mezon\Gui\WidgetsRegistry\BootstrapWidgets;
use Mezon\TemplateEngine\TemplateEngine;
use Mezon\Functional\Functional;

/**
 * Class ListBuilder
 *
 * @package CrudService
 * @subpackage ListBuilder
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/12)
 * @copyright Copyright (c) 2019, aeon.org
 */
define('DESCRIPTION_FIELD_NAME', 'description');

/**
 * Class constructs grids.
 */
class ListBuilder
{

    /**
     * Fields
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Service logic adapter
     *
     * @var \Mezon\Gui\ListBuilder\ListBuilderAdapter
     */
    protected $listBuilderAdapter = false;

    /**
     * List item transformation callback
     *
     * @var array
     */
    protected $recordTransformer = [];

    /**
     * Custom actions for each record
     *
     * @var string
     */
    private $customActions = null;

    /**
     * Constructor
     *
     * @param array $fields
     *            List of fields
     * @param \Mezon\Gui\ListBuilder\ListBuilderAdapter $listBuilderAdapter
     *            Adapter for the data source
     */
    public function __construct(array $fields, ListBuilderAdapter $listBuilderAdapter)
    {
        $transformedFields = [];

        foreach ($fields as $i => $field) {
            $key = is_array($field) ? $i : $field;
            $transformedFields[$key] = is_array($field) ? $field : [
                'title' => $field
            ];
        }

        $this->fields = $transformedFields;

        $this->listBuilderAdapter = $listBuilderAdapter;
    }

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
     * Setting record transformer
     *
     * @param mixed $recordTransformer
     *            callable record transformer
     * @codeCoverageIgnore
     */
    public function setRecordTransformer($recordTransformer): void
    {
        $this->recordTransformer = $recordTransformer;
    }

    /**
     * Method returns end point for the create page form
     *
     * @return string Create page endpoint
     */
    protected function getCreatePageEndpoint(): string
    {
        if (isset($_GET['create-page-endpoint'])) {
            return $_GET['create-page-endpoint'];
        }

        return '../create/';
    }

    /**
     * Method shows "no records" message instead of listing
     *
     * @return string Compiled list view
     */
    protected function listingNoItems(): string
    {
        $content = BootstrapWidgets::get('listing-no-items');

        return str_replace('{create-page-endpoint}', $this->getCreatePageEndpoint(), $content);
    }

    /**
     * Method displays list of possible buttons
     *
     * @param int $id
     *            Id of the record
     * @return string Compiled list buttons
     */
    protected function listOfButtons(int $id): string
    {
        $content = BootstrapWidgets::get('list-of-buttons');

        return str_replace('{id}', $id, $content);
    }

    /**
     * Need to display actions in list
     *
     * @return bool Do we need add actions
     */
    protected function needActions(): bool
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
     * @param bool $addActions
     *            Do we need to add actions
     * @return string Compiled row
     */
    protected function listingItemsCells($record, bool $addActions = true): string
    {
        $content = '';

        foreach ($this->fields as $name => $data) {
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

        if ($addActions && $this->needActions()) {
            $content .= BootstrapWidgets::get('listing-actions');

            $content = str_replace(
                '{actions}',
                $this->customActions === null ? $this->listOfButtons(Fetcher::getField($record, 'id')) : $this->customActions,
                $content);
        }

        return $content;
    }

    /**
     * Method transforms database record
     *
     * @param array $record
     *            Transforming record
     * @return object Transformed record
     */
    protected function transformRecord(object $record): object
    {
        // here we assume that we get from service
        // already transformed
        // and here we provide only additional transformations
        if (is_callable($this->recordTransformer)) {
            $record = call_user_func($this->recordTransformer, $record);
        }

        return $record;
    }

    /**
     * Method compiles listing items
     *
     * @param array $records
     *            Listof records
     * @return string Compiled list items
     */
    protected function listingItems(array $records): string
    {
        $content = '';

        foreach ($records as $record) {
            $content .= BootstrapWidgets::get('listing-row');
            $content = str_replace('{items}', $this->listingItemsCells($record), $content);

            $record = $this->transformRecord($record);

            $record = $this->listBuilderAdapter->preprocessListItem($record);

            $content = TemplateEngine::printRecord($content, $record);
        }

        return $content;
    }

    /**
     * Method compiles header cells
     *
     * @param bool $addActions
     *            Do we need to add actions
     * @return string Compiled header
     */
    protected function listingHeaderCells(bool $addActions = true): string
    {
        $content = '';

        foreach ($this->fields as $name => $data) {
            if ($name == 'domain_id') {
                continue;
            }

            $idStyle = $name == 'id' ? 'style="text-align: center;"' : '';

            $content .= BootstrapWidgets::get('listing-header-cell');
            $content = str_replace([
                '{id-style}',
                '{title}'
            ], [
                $idStyle,
                $data['title']
            ], $content);
        }

        if ($addActions && $this->needActions()) {
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
    protected function listingHeaderContent(): string
    {
        if (@$_GET['create-button'] == 1) {
            $content = BootstrapWidgets::get('listing-header');

            $content = str_replace('{create-page-endpoint}', $this->getCreatePageEndpoint(), $content);
        } else {
            $content = BootstrapWidgets::get('simple-listing-header');
        }

        return $content;
    }

    /**
     * Method compiles listing header
     *
     * @return string Compiled header
     */
    protected function listingHeader(): string
    {
        $content = $this->listingHeaderContent();

        $content = str_replace(
            '{description}',
            isset($_GET[DESCRIPTION_FIELD_NAME]) ? $_GET[DESCRIPTION_FIELD_NAME] : 'Выберите необходимое действие',
            $content);

        return str_replace('{cells}', $this->listingHeaderCells(), $content);
    }

    /**
     * Method compiles listing header
     *
     * @return string Compiled header
     */
    protected function simpleListingHeader(): string
    {
        $content = BootstrapWidgets::get('simple-listing-header');

        $content = str_replace(
            '{description}',
            isset($_GET[DESCRIPTION_FIELD_NAME]) ? $_GET[DESCRIPTION_FIELD_NAME] : 'Выберите необходимое действие',
            $content);

        return str_replace('{cells}', $this->listingHeaderCells(false), $content);
    }

    /**
     * Method compiles listing items
     *
     * @param array $records
     *            List of records
     * @return string Compiled simple list
     */
    protected function simpleListingItems(array $records): string
    {
        $content = '';

        foreach ($records as $record) {
            $content .= str_replace(
                '{items}',
                $this->listingItemsCells($record, false),
                BootstrapWidgets::get('listing-row'));

            $record = $this->transformRecord($record);

            $record = $this->listBuilderAdapter->preprocessListItem($record);

            $content = TemplateEngine::printRecord($content, $record);
        }

        return $content;
    }

    /**
     * Method compiles listing form
     *
     * @return string Compiled listing form
     */
    public function listingForm(): string
    {
        // TODO split into SimpleListBuilder and ListBuilder
        $records = $this->listBuilderAdapter->getRecords([
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

    /**
     * Method compiles simple_listing form
     *
     * @return string Compiled simple listing form
     */
    public function simpleListingForm(): string
    {
        $records = $this->listBuilderAdapter->all();

        if (! empty($records)) {
            $header = $this->simpleListingHeader();

            $items = $this->simpleListingItems($records);

            // they are the same with full feature listing
            $footer = BootstrapWidgets::get('listing-footer');

            return $header . $items . $footer;
        } else {
            return BootstrapWidgets::get('listing-no-items');
        }
    }

    /**
     * Method returns fields of the list
     *
     * @return array fields list
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
