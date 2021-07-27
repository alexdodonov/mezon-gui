<?php
namespace Mezon\Gui\Tests\Common;

use Mezon\Gui\ListBuilder;
use Mezon\Gui\Tests\ListBuilderTestsBase;
use Mezon\Gui\Tests\FakeAdapter;

class ConstructorUnitTest extends ListBuilderTestsBase
{

    /**
     * Testing constructor
     */
    public function testConstructorValid(): void
    {
        // setup and test body
        $listBuilder = new ListBuilder\Common($this->getFields(), new FakeAdapter());

        // assertions
        $this->assertIsArray($listBuilder->getFields(), 'Invalid fields list type');
    }
}
