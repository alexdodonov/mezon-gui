<?php

class TextFieldUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor()
    {
        // setupp
        $field = new \Mezon\Gui\Field\TextField([
            'text' => 'name'
        ]);

        // test bodyy
        $content = $field->html();

        // assertionss
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
