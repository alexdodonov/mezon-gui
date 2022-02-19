<?php
namespace Mezon\Gui\Field\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Gui\FormBuilder\RowsField;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class RowsFieldUnitTest extends TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor(): void
    {
        // setupp
        $field = new RowsField([
            'title' => [
                'type' => 'string'
            ]
        ], 'author');

        // test bodyy
        $content = $field->html();

        // assertionss
        $this->assertStringContainsString('add_element_by_template', $content, 'Necessary JavaScripts were not found');
    }

    /**
     * Testing fillAllRow method
     */
    public function testFillAllRow(): void
    {
        // setupp
        $field = new \Mezon\Gui\FormBuilder\RowsField([
            'title' => [
                'type' => 'string'
            ]
        ], 'author');

        // test body and assertionss
        $this->assertTrue($field->fillAllRow());
    }
}
