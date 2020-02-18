<?php

class CustomFieldUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Method returns mock object of the custom field
     *
     * @return object mock object of the custom field
     */
    protected function getFieldMock(): object
    {
        $mock = $this->getMockBuilder(\Mezon\Gui\Field\CustomField::class)
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
                    'type' => 'integer',
                    'fields' => [],
                    'class' => 'cls'
                ],
                ''
            ])
            ->setMethods([
            'get_field_template'
        ])
            ->getMock();

        $mock->method('get_field_template')->willReturn(
            'class:{class} name:{name} required:{required} disabled:{disabled} custom:{custom} name-prefix:{name-prefix} batch:{batch} toggler:{toggler} toggler:{toggle-value}');

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
        $this->assertStringContainsString('name:name', $content);
        $this->assertStringContainsString('required:1', $content);
        $this->assertStringContainsString('disabled:1', $content);
        $this->assertStringContainsString('custom:1', $content);
        $this->assertStringContainsString('name-prefix:prefix', $content);
        $this->assertStringContainsString('batch:1', $content);
        $this->assertStringContainsString('toggler:toggler-name', $content);
        $this->assertStringContainsString('toggler:3', $content);
        $this->assertStringContainsString('class:cls', $content);
    }
}
