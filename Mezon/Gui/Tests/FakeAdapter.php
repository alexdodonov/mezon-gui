<?php
namespace Mezon\Gui\Tests;

use Mezon\Gui\ListBuilder\ListBuilderAdapter;

class FakeAdapter implements ListBuilderAdapter
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

        foreach ($this->records as $i => $record) {
            $this->records[$i] = (object) $record;
        }
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
     *
     * {@inheritdoc}
     * @see ListBuilderAdapter::preprocessListItem($record)
     */
    public function preprocessListItem(object $record): object
    {
        return $record;
    }
}
