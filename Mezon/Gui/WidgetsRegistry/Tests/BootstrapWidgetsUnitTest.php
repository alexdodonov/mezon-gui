<?php
namespace Mezon\Gui\WidgetsRegistry\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Gui\WidgetsRegistry\BootstrapWidgets;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class BootstrapWidgetsUnitTest extends TestCase
{

    /**
     * Getting template
     */
    public function testGetTemplate(): void
    {
        // setup
        $bootstrapWidgets = new BootstrapWidgets();

        // test body
        $widget = $bootstrapWidgets->getWidget('table-cell-start');

        // assertions
        $this->assertStringContainsString('<td', $widget, 'Content of the widget "table-cell-start" was not loaded');
    }
}
