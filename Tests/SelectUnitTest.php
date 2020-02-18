<?php

class SelectUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor()
    {
        // setup
        $field = new \Mezon\Gui\Field\Select(
            [
                'name' => 'name',
                'required' => 1,
                'disabled' => 1,
                'name-prefix' => 'prefix',
                'batch' => 1,
                'toggler' => 'toggler-name',
                'toggle-value' => 3,
                'items' => [
                    '1' => '111',
                    '2' => '222'
                ],
                'type' => 'integer',
                'class' => 'cls'
            ],
            '');

        // test body
        $content = $field->html();

        // assertions
        $this->assertStringContainsString('<select ', $content);
        $this->assertStringContainsString('name="prefix-name[{_creation_form_items_counter}]"', $content);
        $this->assertStringContainsString('required="required"', $content);
        $this->assertStringContainsString('disabled', $content);
        $this->assertStringContainsString('toggler="toggler-name"', $content);
        $this->assertStringContainsString('toggle-value="3"', $content);
        $this->assertStringContainsString('class="cls"', $content);
    }
}
