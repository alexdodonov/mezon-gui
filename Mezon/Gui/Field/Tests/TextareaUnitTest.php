<?php
namespace Mezon\Gui\Field\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Gui\Field\Textarea;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class TextareaUnitTest extends TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor(): void
    {
        // setup
        $field = new Textarea(
            [
                'name' => 'name',
                'required' => 1,
                'disabled' => 1,
                'name-prefix' => 'prefix',
                'batch' => 1,
                'toggler' => 'toggler-name',
                'toggle-value' => 3,
                'type' => 'string',
                'class' => 'cls'
            ],
            '');

        // test body
        $content = $field->html();

        // assertions
        $this->assertStringContainsString('<textarea ', $content);
        $this->assertStringContainsString('name="prefix-name[{_creation_form_items_counter}]"', $content);
        $this->assertStringContainsString('required="required"', $content);
        $this->assertStringContainsString('disabled', $content);
        $this->assertStringContainsString('toggler="toggler-name"', $content);
        $this->assertStringContainsString('toggle-value="3"', $content);
        $this->assertStringContainsString(' cls"', $content);
    }
}
