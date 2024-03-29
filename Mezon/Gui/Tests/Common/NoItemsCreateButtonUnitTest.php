<?php
namespace Mezon\Gui\Tests\Common;

use Mezon\Gui\ListBuilder;
use Mezon\Gui\Tests\FakeAdapter;
use Mezon\Gui\Tests\ListBuilderTestsBase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class NoItemsCreateButtonUnitTest extends ListBuilderTestsBase
{

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function actionsDataProvider(): array
    {
        return [
            // #0, the first case - empty listing
            [
                function (): object {
                    // setup
                    unset($_GET['create-button']);
                    return new ListBuilder\Common($this->getFields(), new FakeAdapter([]));
                },
                function (string $result): void {
                    // asserting method
                    $this->assertStringNotContainsString('create-button"', $result);
                }
            ],
            // #1, the second case - full header
            [
                function (): object {
                    // setup
                    $_GET['create-button'] = 1;
                    if(isset($_GET['create-page-endpoint'])){
                        unset($_GET['create-page-endpoint']);
                    }
                    return new ListBuilder\Common($this->getFields(), new FakeAdapter([]));
                },
                function (string $result): void {
                    // asserting method
                    $this->assertStringContainsString('../create/', $result);
                }
            ],
        ];
    }

    /**
     * Testing create button absence
     *
     * * @dataProvider actionsDataProvider
     */
    public function testCreateButtonAbsenceForEmptyList(callable $setup, callable $assertions): void
    {
        // setup
        $obj = $setup();

        // test body
        $result = $obj->listingForm();

        // assertions
        $assertions($result);
    }
}
