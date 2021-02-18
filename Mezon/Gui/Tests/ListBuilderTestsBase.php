<?php
namespace Mezon\Gui\Tests;

use PHPUnit\Framework\TestCase;

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
            'title'
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
