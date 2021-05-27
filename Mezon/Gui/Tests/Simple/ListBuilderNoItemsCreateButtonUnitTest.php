<?php
namespace Mezon\Gui\Tests\Simple;

use Mezon\Gui\ListBuilder;
use Mezon\Gui\Tests\FakeAdapter;
use Mezon\Gui\Tests\ListBuilderTestsBase;

class ListBuilderNoItemsCreateButtonUnitTest extends ListBuilderTestsBase
{

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function actionsDataProvider(): array
    {
        return [
            // #0, the first case - without button
            [
                function (): object {
                    // setup
                    unset($_GET['create-button']);
                    return new ListBuilder\Simple($this->getFields(), new FakeAdapter([]));
                }
            ],
            // #1, the second case - without button, even if we try to display it
            [
                function (): object {
                    // setup
                    $_GET['create-button'] = 1;
                    return new ListBuilder\Simple($this->getFields(), new FakeAdapter([]));
                }
            ],
        ];
    }

    /**
     * Testing create button absence
     *
     * @param callable $setup
     *            setup method
     * @dataProvider actionsDataProvider
     */
    public function testCreateButtonAbsenceForEmptyList(callable $setup): void
    {
        // setup
        $obj = $setup();

        // test body
        $result = $obj->listingForm();

        // assertions
        $this->assertStringNotContainsString('create-button"', $result);
    }
}
