<?php

class BootstrapWidgetsUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Getting template
     */
    public function testGetTemplate()
    {
        // setup
        $bootstrapWidgets = new \Mezon\Gui\WidgetsRegistry\BootstrapWidgets();

        // test body
        $widget = $bootstrapWidgets->getWidget('table-cell-start');

        // assertions
        $this->assertStringContainsString('<td', $widget, 'Content of the widget "table-cell-start" was not loaded');
    }
}
