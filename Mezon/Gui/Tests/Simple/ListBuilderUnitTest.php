<?php
namespace Mezon\Gui\Tests\Simple;

use Mezon\Gui\ListBuilder;
use Mezon\Router\Router;
use Mezon\Transport\Request;
use Mezon\Gui\Tests\ListBuilderTestsBase;
use Mezon\Gui\Tests\FakeAdapter;
use Mezon\Functional\Functional;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ListBuilderUnitTest extends ListBuilderTestsBase
{

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $router = new Router();

        Request::registerRouter($router);

        Conf::setConfigStringValue('headers/layer', 'mock');
    }

    /**
     * Asserting that string contains substrings
     *
     * @param array $needles
     * @param string $haystack
     */
    private function assertStringContainsStrings(array $needles, string $haystack): void
    {
        foreach ($needles as $needle) {
            $this->assertStringContainsString($needle, $haystack);
        }
    }

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function commonBehaviourDataProvider(): array
    {
        $assert = function (string $result): void {
            // asserting method
            $this->assertStringNotContainsString('!1!', $result);
            $this->assertStringNotContainsString('!2!', $result);
        };

        return [
            // #0, listingForm, custom title and description
            [
                function (): object {
                    // setup method
                    $listBuilder = new ListBuilder\Simple($this->getFields(), new FakeAdapter($this->getRecords()));
                    $listBuilder->listTitle = 'List Title';
                    $listBuilder->listDescription = 'List Description';
                    return $listBuilder;
                },
                function (string $result) use ($assert) {
                    $assert($result);

                    $this->assertRegExp('/\/thead[\S\s]*\/td[\S\s]*\/tbody/m', $result);

                    $this->assertStringContainsStrings(
                        [
                            'style="text-align: center; width:5%;">id<',
                            'td style="text-align: center;">1<',
                            'td style="text-align: center;">2<',
                            '>title<',
                            '{action-message}',
                            'List Title',
                            'List Description'
                        ],
                        $result);
                }
            ],
            // #1, listingForm, default title and description
            [
                function (): object {
                    // setup method
                    return new ListBuilder\Simple($this->getFields(), new FakeAdapter($this->getRecords()));
                },
                function (string $result) use ($assert) {
                    $assert($result);

                    $this->assertStringContainsStrings([
                        'Список записей',
                        'Выберите необходимое действие'
                    ], $result);
                }
            ],
            // #2, listingForm, no records
            [
                function (): object {
                    // setup method
                    $listBuilder = new ListBuilder\Simple($this->getFields(), new FakeAdapter([]));
                    $listBuilder->listTitle = 'Some list title';
                    return $listBuilder;
                },
                function (string $result) use ($assert) {
                    $assert($result);

                    $this->assertStringContainsStrings(
                        [
                            'class="no-items-title"',
                            '{action-message}',
                            'Ни одной записи не найдено',
                            'Some list title'
                        ],
                        $result);
                }
            ],
            // #3, listingForm, transformation
            [
                function (): object {
                    // setup method
                    $listBuilder = new ListBuilder\Simple($this->getFields(), new FakeAdapter($this->getRecords()));
                    $listBuilder->listTitle = 'Some list title';
                    $listBuilder->setRecordTransformer(
                        function (object $record): object {
                            Functional::setField($record, 'transformed', 'transformed!');
                            return $record;
                        });
                    return $listBuilder;
                },
                function (string $result) use ($assert) {
                    $assert($result);

                    $this->assertStringContainsStrings([
                        'transformed!'
                    ], $result);
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
     * @dataProvider commonBehaviourDataProvider
     */
    public function testCommonBehaviour(callable $setup, callable $assertions): void
    {
        // setup
        $obj = $setup();

        // test body
        $result = $obj->listingForm();

        // assertions
        $assertions($result);
    }
}
