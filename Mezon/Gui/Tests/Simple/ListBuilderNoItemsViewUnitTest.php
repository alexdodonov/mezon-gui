<?php
namespace Mezon\Gui\Tests\Simple;

use Mezon\Gui\ListBuilder;
use Mezon\Gui\Tests\FakeAdapter;
use Mezon\Gui\Tests\ListBuilderTestsBase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ListBuilderNoItemsViewUnitTest extends ListBuilderTestsBase
{

    /**
     * Testing no items view
     */
    public function testNoItemsView(): void
    {
        // setup
        $listBuilder = new ListBuilder\Simple($this->getFields(), new FakeAdapter([]));
        $listBuilder->setNoItemsView('no items!');
        $listBuilder->listTitle = 'title';

        // test body
        $result = $listBuilder->listingForm();

        // assertions
        $this->assertStringContainsString('title', $result);
        $this->assertStringContainsString('Ни одной записи не найдено', $result);
        $this->assertStringContainsString('no items!', $result);
    }
}
