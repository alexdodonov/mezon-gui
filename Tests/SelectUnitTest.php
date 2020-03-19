<?php

class SelectUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Field settings
     *
     * @return array select field settings
     */
    protected function getSettings(): array
    {
        return [
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
        ];
    }

    /**
     * Testing constructor
     */
    public function testConstructor()
    {
        // setupp
        $field = new \Mezon\Gui\Field\Select($this->getSettings(), '');

        // test bodyy
        $content = $field->html();

        // assertionss
        $this->assertStringContainsString('<select ', $content);
        $this->assertStringContainsString('name="prefix-name[{_creation_form_items_counter}]"', $content);
        $this->assertStringContainsString('required="required"', $content);
        $this->assertStringContainsString('disabled', $content);
        $this->assertStringContainsString('toggler="toggler-name"', $content);
        $this->assertStringContainsString('toggle-value="3"', $content);
        $this->assertStringContainsString('class="cls"', $content);
    }

    /**
     * Testing constructor with type wich is defaulted to integer
     */
    public function testConstructorWithDefaultedType()
    {
        // setup
        $settings = $this->getSettings();
        unset($settings['type']);
        $field = new \Mezon\Gui\Field\Select($settings, '');

        // test body and assertions
        $this->assertEquals('integer', $field->getType());
    }
}
