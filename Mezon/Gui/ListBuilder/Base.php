<?php
namespace Mezon\Gui\ListBuilder;

use Mezon\Gui\WidgetsRegistry\BootstrapWidgets;

class Base
{

    // TODO make private
    /**
     * Fields
     *
     * @var array
     */
    protected $fields = [];

    // TODO make private
    /**
     * Service logic adapter
     *
     * @var \Mezon\Gui\ListBuilder\ListBuilderAdapter
     */
    protected $listBuilderAdapter = false;

    // TODO make private
    /**
     * List item transformation callback
     *
     * @var array
     */
    protected $recordTransformer = [];

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
} 
