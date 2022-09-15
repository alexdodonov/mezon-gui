<?php
namespace Mezon\Gui\Field\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Gui\Field\Select;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SelectUnitTest extends TestCase
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
    public function testConstructor(): void
    {
        // setup
        $field = new Select($this->getSettings(), '');

        // test body
        $content = $field->html();

        // assertions
        $this->assertStringContainsString('<select class="cls"', $content);
        $this->assertStringContainsString('name="prefix-name[{_creation_form_items_counter}]"', $content);
        $this->assertStringContainsString('required="required"', $content);
        $this->assertStringContainsString('disabled', $content);
        $this->assertStringContainsString('toggler="toggler-name"', $content);
        $this->assertStringContainsString('toggle-value="3"', $content);
    }

    /**
     * Testing constructor with type wich is defaulted to integer
     */
    public function testConstructorWithDefaultedType(): void
    {
        // setup
        $settings = $this->getSettings();
        unset($settings['type']);
        $field = new Select($settings, '');

        // test body and assertions
        $this->assertEquals('integer', $field->getType());
    }

    /**
     * Testing constructor when records are fetched from data source
     */
    public function testConstructorWithDataSource(): void
    {
        // setup
        $settings = $this->getSettings();
        $settings['items'] = function (): array {
            return [
                '2' => '222',
                '3' => '333'
            ];
        };
        $field = new Select($settings, '');

        // test body
        $content = $field->html();

        // assertions
        $this->assertStringContainsString('<option value="2"', $content);
        $this->assertStringContainsString('<option value="3"', $content);
    }
}
