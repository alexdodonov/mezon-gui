<?php

class LabelFieldUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor()
    {
        // setup
        $field = new \Mezon\Gui\Field\LabelField([
            'text' => 'name'
        ]);

        // test body
        $content = $field->html();

        // assertions
        $this->assertStringContainsString('<label class="control-label">name</label>', $content, 'Label was not generated');
    }

    /**
     * Testing getType method
     */
    public function testGetType(): void
    {
        // setup
        $field = new \Mezon\Gui\Field\LabelField([
            'text' => 'name'
        ]);

        // test body and assertions
        $this->assertStringContainsString('label', $field->getType());
    }

    /**
     * Testing fillAllRow method
     */
    public function testFillAllRow(): void
    {
        // setup
        $field = new \Mezon\Gui\Field\LabelField([
            'text' => 'name'
        ]);

        // test body and assertions
        $this->assertTrue($field->fillAllRow());
    }
}
