<?php
namespace Mezon\Gui\Field\Tests;

class TextFieldUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor()
    {
        // setup
        $field = new \Mezon\Gui\Field\TextField([
            'text' => 'name'
        ]);

        // test body
        $content = $field->html();

        // assertions
        $this->assertEquals('name', $content, 'Text was not fetched');
    }

    /**
     * Testing fillAllRow method
     */
    public function testFillAllRow(): void
    {
        // setupp
        $field = new \Mezon\Gui\Field\TextField([
            'text' => 'name'
        ]);

        // test body and assertionss
        $this->assertTrue($field->fillAllRow());
    }
}
