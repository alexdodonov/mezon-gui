<?php
namespace Mezon\Gui\FormBuilder\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Gui\FormBuilder\FormHeader;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FormHeaderUnitTest extends TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor(): void
    {
        // setup
        $field = new FormHeader([
            'text' => 'name'
        ]);

        // test body
        $content = $field->html();

        // assertions
        $this->assertStringContainsString('<h3>name</h3>', $content, 'Header was not built');
    }

    /**
     * Testing fillAllRow method
     */
    public function testFillAllRow(): void
    {
        // setup
        $field = new FormHeader([
            'text' => 'name'
        ]);

        // test body and assertions
        $this->assertFalse($field->fillAllRow());
    }
}
