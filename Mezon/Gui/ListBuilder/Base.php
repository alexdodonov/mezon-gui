<?php
namespace Mezon\Gui\ListBuilder;

use Mezon\Gui\WidgetsRegistry\BootstrapWidgets;

class Base
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
     * @var ListBuilderAdapter
     */
    private $listBuilderAdapter;

    /**
     * List item transformation callback
     *
     * @var array
     */
    private $recordTransformer = [];

    /**
     * List title
     *
     * @var string
     */
    public $listTitle = 'Список записей';

    /**
     * List description
     *
     * @var string
     */
    public $listDescription = 'Выберите необходимое действие';

    /**
     * Name of the template for the empty records list
     *
     * @var string
     */
    protected $noItemsTemplateName = 'listing-no-items';

    /**
     * No items block name
     *
     * @var string
     */
    private $noItemsView = '';

    /**
     * Constructor
     *
     * @param array $fields
     *            List of fields
     * @param ListBuilderAdapter $listBuilderAdapter
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
     * Method sets field $noItemsView
     *
     * @param string $noItemsView
     */
    public function setNoItemsView(string $noItemsView): void
    {
        $this->noItemsView = $noItemsView;
    }

    /**
     * Method returns content for the case when no items were found
     *
     * @return string content for the case when no items were found
     */
    protected function getNoItemsContent(): string
    {
        return str_replace([
            '{list-description}',
            '{list-title}',
            '{extra-message}'
        ], [
            'Ни одной записи не найдено',
            $this->listTitle,
            $this->noItemsView
        ], BootstrapWidgets::get($this->noItemsTemplateName));
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
     * Method returns fields
     *
     * @return array fields
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Method returns list builder adapter
     *
     * @return ListBuilderAdapter
     */
    protected function getListBuilderAdapter(): ListBuilderAdapter
    {
        return $this->listBuilderAdapter;
    }

    /**
     * Method transforms database record
     *
     * @param object $record
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
     * Method compiles listing items cells
     *
     * @return string Compiled row
     */
    protected function listingItemsCells(): string
    {
        $content = '';

        foreach (array_keys($this->getFields()) as $name) {
            if ($name === 'domain_id') {
                continue;
            }
            if ($name === 'id') {
                $content = BootstrapWidgets::get('listing-row-centered-cell');
            } else {
                $content .= BootstrapWidgets::get('listing-row-cell');
            }
            $content = str_replace('{name}', '{' . $name . '}', $content);
        }

        return $content;
    }

    /**
     * Method compiles header cells
     *
     * @return string Compiled header
     */
    protected function listingHeaderCells(): string
    {
        $content = '';

        foreach ($this->getFields() as $name => $data) {
            if ($name === 'domain_id') {
                continue;
            }

            $idStyle = $name === 'id' ? 'style="text-align: center; width:5%;"' : '';

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
} 
