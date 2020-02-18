<?php

class InputDateUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor()
    {
        // setup
        $field = new \Mezon\Gui\Field\InputDate(
            [
                'name' => 'name',
                'required' => 1,
                'disabled' => 1,
                'name-prefix' => 'prefix',
                'batch' => 1,
                'toggler' => 'toggler-name',
                'toggle-value' => 3,
                'type' => 'date',
                'class' => 'cls'
            ],
            '');

        // test body
        $content = $field->html();

        // assertions
        $this->assertStringContainsString('<input ', $content);
        $this->assertStringContainsString('type="text"', $content);
        $this->assertStringContainsString('date-input', $content);
        $this->assertStringContainsString('name="prefix-name[{_creation_form_items_counter}]"', $content);
        $this->assertStringContainsString('required="required"', $content);
        $this->assertStringContainsString('disabled', $content);
        $this->assertStringContainsString('toggler="toggler-name"', $content);
        $this->assertStringContainsString('toggle-value="3"', $content);
        $this->assertStringContainsString('class="cls date', $content);
    }
}
