<?php
namespace Mezon\Gui\Tests;

class FieldUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testNoNameException()
    {
        $this->expectException(\Exception::class);
        new \Mezon\Gui\Field([], '');
    }

    /**
     * Testing setters
     */
    public function testNameSetter()
    {
        // test body
        $field = new \Mezon\Gui\Field(json_decode(file_get_contents(__DIR__ . '/conf/name-setter.json'), true), '');

        // assertions
        $this->assertStringContainsString('prefixfield-name000', $field->html(), 'Invalid field "name" value');
    }

    /**
     * Testing setters
     */
    public function testRequiredSetter()
    {
        // test body
        $field = new \Mezon\Gui\Field(json_decode(file_get_contents(__DIR__ . '/conf/required-setter.json'), true), '');

        // assertions
        $this->assertStringContainsString('prefixfield-name1111select2', $field->html(), 'Invalid field "name" value');
    }

    /**
     * Testing exception if type not set
     */
    public function testTypeException(): void
    {
        // setup and assertions
        $this->expectExceptionCode(- 2);

        // test body
        new \Mezon\Gui\Field([], '');
    }

    /**
     * Testing setters
     */
    public function testHasLabelSetter()
    {
        // test body
        $field = new \Mezon\Gui\Field(json_decode(file_get_contents(__DIR__ . '/conf/has-label-setter.json'), true), '');

        // assertions
        $this->assertTrue($field->hasLabel());
    }
}
