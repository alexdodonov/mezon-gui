<?php
namespace Mezon\Gui\Field\Tests;

use Mezon\Gui\Field\RecordField;
use PHPUnit\Framework\TestCase;

class RecordFieldUnitTest extends TestCase
{

    /**
     * Method returns mock object of the custom field
     *
     * @return object mock object of the custom field
     */
    protected function getFieldMock(): object
    {
        $mock = $this->getMockBuilder(RecordField::class)
            ->setConstructorArgs(
            [
                [
                    'name' => 'name',
                    'required' => 1,
                    'disabled' => 1,
                    'custom' => 1,
                    'name-prefix' => 'prefix',
                    'batch' => 1,
                    'toggler' => 'toggler-name',
                    'toggle-value' => 3,
                    'bind-field' => 'id',
                    'session-id' => 'sid',
                    'remote-source' => 'http://ya.ru',
                    'type' => 'remote',
                    'layout' => [
                        'rows' => [
                            [
                                "remote" => [
                                    "width" => 10
                                ]
                            ]
                        ]
                    ]
                ],
                ''
            ])
            ->setMethods([
            'getFields'
        ])
            ->getMock();

        $mock->method('getFields')->willReturn(
            [
                'id' => [
                    'type' => 'integer'
                ],
                'remote' => [
                    'type' => 'string',
                    'title' => 'remote-title'
                ]
            ]);

        return $mock;
    }

    /**
     * Testing constructor
     */
    public function testConstructor()
    {
        // setup
        $field = $this->getFieldMock();

        // test body
        $content = $field->html();

        // assertions
        $this->assertStringContainsString('name="prefix-remote"', $content, 'Name of the remote record was not found');
    }
}
