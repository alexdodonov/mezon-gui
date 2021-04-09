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
    private $listBuilderAdapter = false;

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
     * Method returns content for the case when no items were found
     *
     * @return string content for the case when no items were found
     */
    public function getNoItemsContent(): string
    {
        return str_replace([
            '{list-description}',
            '{list-title}'
        ], [
            'Ни одной записи не найдено',
            $this->listTitle
        ], BootstrapWidgets::get('listing-no-items'));
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
} 
