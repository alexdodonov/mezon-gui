<?php
namespace Mezon\Gui\Field\Tests;

class CheckboxesFieldUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Method returns mock object of the custom field
     *
     * @return object mock object of the custom field
     */
    protected function getFieldMock(array $items = [
        [
            'id' => 1
        ]
    ]): object
    {
        $mock = $this->getMockBuilder(\Mezon\Gui\Field\CheckboxesField::class)
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
                    'type' => 'int',
                    'class' => 'cls'
                ],
                ''
            ])
            ->setMethods([
            'getExternalRecords'
        ])
            ->getMock();

        $mock->method('getExternalRecords')->willReturn($items);

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
        $this->assertStringContainsString('type="checkbox"', $content);
        $this->assertStringContainsString('class="cls"', $content);
    }

    /**
     * Testing constructor
     */
    public function testConstructorWithTitle()
    {
        // setup
        $field = $this->getFieldMock([
            [
                'id' => 1,
                'title' => 'item-title'
            ]
        ]);

        // test body
        $content = $field->html();

        // assertions
        $this->assertStringContainsString('item-title', $content);
    }
}
