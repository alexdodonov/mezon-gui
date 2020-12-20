<?php
namespace Mezon\Gui\ListBuilder;

use Mezon\Gui\WidgetsRegistry\BootstrapWidgets;
use Mezon\TemplateEngine\TemplateEngine;

/**
 * Class Simple
 *
 * @package GUI
 * @subpackage ListBuilder
 * @author Dodonov A.A.
 * @version v.1.0 (2020/12/19)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Class constructs grids
 */
class Simple
{

    /**
     * Fields
     *
     * @var array
     */
    private $fields = [];

    /**
     * Service logic adapter
     *
     * @var \Mezon\Gui\ListBuilder\ListBuilderAdapter
     */
    private $listBuilderAdapter = false;

    /**
     * List item transformation callback
     *
     * @var array
     */
    private $recordTransformer = [];

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
     * Method compiles listing items cells
     *
     * @return string Compiled row
     */
    private function listingItemsCells(): string
    {
        $content = '';

        foreach (array_keys($this->fields) as $name) {
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

        return $content;
    }

    /**
     * Method transforms database record
     *
     * @param array $record
     *            Transforming record
     * @return object Transformed record
     */
    private function transformRecord(object $record): object
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
     * Method compiles header cells
     *
     * @return string Compiled header
     */
    private function listingHeaderCells(): string
    {
        $content = '';

        foreach ($this->fields as $name => $data) {
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

        return $content;
    }

    /**
     * Method compiles listing header
     *
     * @return string Compiled header
     */
    private function simpleListingHeader(): string
    {
        $content = BootstrapWidgets::get('simple-listing-header');

        // TODO use Request
        $content = str_replace(
            '{description}',
            isset($_GET['description']) ? $_GET['description'] : 'Выберите необходимое действие',
            $content);

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

            $record = $this->listBuilderAdapter->preprocessListItem($record);

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
