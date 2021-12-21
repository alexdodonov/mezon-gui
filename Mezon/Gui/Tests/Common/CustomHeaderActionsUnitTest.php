<?php
namespace Mezon\Gui\Tests\Common;

use Mezon\Gui\ListBuilder;
use Mezon\Gui\Tests\FakeAdapter;
use Mezon\Gui\Tests\ListBuilderTestsBase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CustomHeaderActionsUnitTest extends ListBuilderTestsBase
{

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function actionsDataProvider(): array
    {
        return [
            // #0, the first case - simple header
            [
                function (): object {
                    // setup
                    unset($_GET['create-button']);
                    $listBuilder = new ListBuilder\Common($this->getFields(), new FakeAdapter($this->getRecords()));
                    $listBuilder->setCustomHeaderActions('custom header actions');
                    return $listBuilder;
                },
                function (string $result): void {
                    // asserting method
                    $this->assertStringContainsString('<form', $result);
                    $this->assertStringContainsString('</form>', $result);
                    $this->assertStringContainsString('method="post"', $result);
                    $this->assertStringContainsString('custom header actions', $result);
                }
            ],
            // #1, the second case - full header
            [
                function (): object {
                    // setup
                    $_GET['create-button'] = 1;
                    $listBuilder = new ListBuilder\Common($this->getFields(), new FakeAdapter($this->getRecords()));
                    $listBuilder->setCustomHeaderActions('custom header actions');
                    return $listBuilder;
                },
                function (string $result): void {
                    // asserting method
                    $this->assertStringContainsString('<form', $result);
                    $this->assertStringContainsString('</form>', $result);
                    $this->assertStringContainsString('method="post"', $result);
                    $this->assertStringContainsString('custom header actions', $result);
                }
            ],
            // #2, the third case - simple header
            [
                function (): object {
                    // setup
                    $_GET['create-button'] = 1;
                    $listBuilder = new ListBuilder\Common($this->getFields(), new FakeAdapter([]));
                    $listBuilder->setCustomHeaderActions('custom header actions');
                    return $listBuilder;
                },
                function (string $result): void {
                    // asserting method
                    $this->assertStringNotContainsString('<form', $result);
                    $this->assertStringNotContainsString('</form>', $result);
                    $this->assertStringNotContainsString('method="post"', $result);
                    $this->assertStringNotContainsString('custom header actions', $result);
                }
            ]
        ];
    }

    /**
     * Testing method
     *
     * @param callable $setup
     *            setup method
     * @param callable $assertions
     *            assertions method
     * @dataProvider actionsDataProvider
     */
    public function testCustomHeaderActions(callable $setup, callable $assertions): void
    {
        // setup
        $obj = $setup();

        // test body
        $result = $obj->listingForm();

        // assertions
        $assertions($result);
    }
}
