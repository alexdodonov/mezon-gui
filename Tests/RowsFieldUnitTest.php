<?php

class RowsFieldUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor()
    {
        // setupp
        $field = new \Mezon\Gui\FormBuilder\RowsField([
            'title' => [
                'type' => 'string'
            ],
        ], 'author');

        // test bodyy
        $content = $field->html();

        // assertionss
        $this->assertStringContainsString('add_element_by_template', $content, 'Necessary JavaScripts were not found');
    }
}
