<?php
namespace Mezon\Gui\Tests;

use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ListBuilderTestsBase extends TestCase
{

    /**
     * Method returns list of fields
     *
     * @return array Fields algorithms object
     */
    protected function getFields(): array
    {
        return [
            'id',
            'domain_id',
            'title',
            'transformed'
        ];
    }

    /**
     * Method returns testing records
     *
     * @return array testing records
     */
    protected function getRecords(): array
    {
        return [
            [
                'id' => 1,
            ],
            [
                'id' => 2,
            ]
        ];
    }
}
