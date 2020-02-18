<?php
namespace Mezon\Gui\ListBuilder;

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
     * Constructor
     *
     * @param array $fields
     *            List of fields
     * @param \Mezon\Gui\ListBuilder\ListBuilderAdapter $listBuilderAdapter
     *            Adapter for the data source
     */
    public function __construct(array $fields, \Mezon\Gui\ListBuilder\ListBuilderAdapter $listBuilderAdapter)
    {
        $this->fields = $fields;

        $this->listBuilderAdapter = $listBuilderAdapter;
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
        $content = \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-no-items');

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
        $content = \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('list-of-buttons');

        return str_replace('{id}', $id, $content);
    }

    /**
     * Need to display actions in list
     *
     * @return bool Do we need add actions
     */
    protected function needActions(): bool
    {
        if (@$_GET['update_button'] == 1 || @$_GET['delete_button'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method compiles listing items cells
     *
     * @param bool $addActions
     *            Do we need to add actions
     * @return string Compiled row
     */
    protected function listingItemsCells(bool $addActions = true): string
    {
        $content = '';

        foreach ($this->fields as $name) {
            if ($name == 'domain_id') {
                continue;
            }
            if ($name == 'id') {
                $content .= \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-row-centered-cell');
            } else {
                $content .= \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-row-cell');
            }
            $content = str_replace('{name}', '{' . $name . '}', $content);
        }

        if ($addActions && $this->needActions()) {
            $content .= \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-actions');
        }

        return $content;
    }

    /**
     * Method transforms database record
     *
     * @param array $record
     *            Transforming record
     * @return array Transformed record
     */
    protected function transformRecord(array $record): array
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
            $record['actions'] = $this->listOfButtons(\Mezon\Functional\Functional::getField($record, 'id'));

            $content .= \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-row');
            $content = str_replace('{items}', $this->listingItemsCells(), $content);

            $record = $this->transformRecord($record);

            $record = $this->listBuilderAdapter->preprocessListItem($record);

            $content = \Mezon\TemplateEngine\TemplateEngine::printRecord($content, $record);
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

        foreach ($this->fields as $name) {
            if ($name == 'domain_id') {
                continue;
            }

            $idClass = $name == 'id' ? ' col-md-1' : '';
            $idStyle = $name == 'id' ? 'style="text-align: center;"' : '';

            $content .= \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-header-cell');
            $content = str_replace([
                '{id-class}',
                '{id-style}',
                '{title}'
            ], [
                $idClass,
                $idStyle,
                $name
            ], $content);
        }

        if ($addActions && $this->needActions()) {
            $content .= \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-header-actions');
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
        if (@$_GET['create_button'] == 1) {
            $content = \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-header');

            $content = str_replace('{create-page-endpoint}', $this->getCreatePageEndpoint(), $content);
        } else {
            $content = \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('simple-listing-header');
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
        $content = \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('simple-listing-header');

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
                $this->listingItemsCells(false),
                \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-row'));

            $record = $this->transformRecord($record);

            $content = \Mezon\TemplateEngine\TemplateEngine::printRecord($content, $record);
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
        $records = $this->listBuilderAdapter->getRecords([
            'field' => 'id',
            'order' => 'ASC'
        ], isset($_GET['from']) ? $_GET['from'] : 0, isset($_GET['limit']) ? $_GET['limit'] : 100);

        if (count($records)) {
            $header = $this->listingHeader();

            $items = $this->listingItems($records);

            $footer = \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-footer');

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

        if (count($records)) {
            $header = $this->simpleListingHeader();

            $items = $this->simpleListingItems($records);

            // they are the same with full feature listing
            $footer = \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-footer');

            return $header . $items . $footer;
        } else {
            return \Mezon\Gui\WidgetsRegistry\BootstrapWidgets::get('listing-no-items');
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
