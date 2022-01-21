<?php
namespace Mezon\Gui\Tests\Simple;

use Mezon\Gui\ListBuilder;
use Mezon\Gui\Tests\ListBuilderTestsBase;
use Mezon\Gui\Tests\FakeAdapter;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ConstructorUnitTest extends ListBuilderTestsBase
{

    /**
     * Testing constructor
     */
    public function testConstructorValid(): void
    {
        // setup and test body
        $listBuilder = new ListBuilder\Simple($this->getFields(), new FakeAdapter());

        // assertions
        $this->assertIsArray($listBuilder->getFields(), 'Invalid fields list type');
    }
}
