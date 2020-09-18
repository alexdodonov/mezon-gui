<?php
namespace Mezon\Gui\Field\Tests;

class FakeAdapter implements \Mezon\Gui\ListBuilder\ListBuilderAdapter
{

    /**
     * Records to be returned
     *
     * @var array
     */
    protected $records = [];

    /**
     * Constructor
     *
     * @param array $records
     */
    public function __construct(array $records = [
        [
            'id' => 1,
        ],
        [
            'id' => 2,
        ]
    ])
    {
        $this->records = $records;
    }

    /**
     * Method returns all vailable records
     *
     * @return array all vailable records
     */
    public function all(): array
    {
        return $this->records;
    }

    /**
     * Method returns a subset from vailable records
     *
     * @param array $order
     *            order settings
     * @param int $from
     *            the beginning of the bunch
     * @param int $limit
     *            the size of the batch
     * @return array subset from vailable records
     */
    public function getRecords(array $order, int $from, int $limit): array
    {
        return $this->all();
    }

    /**
     * Record preprocessor
     *
     * @param array $record
     *            record to be preprocessed
     * @return array preprocessed record
     */
    public function preprocessListItem(array $record): array
    {
        return $record;
    }
}
