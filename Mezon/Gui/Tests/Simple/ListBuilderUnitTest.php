<?php
namespace Mezon\Gui\Tests\Simple;

use Mezon\Gui\ListBuilder;
use Mezon\Router\Router;
use Mezon\Transport\Request;
use Mezon\Gui\Tests\ListBuilderTestsBase;
use Mezon\Gui\Tests\FakeAdapter;
use Mezon\Functional\Functional;

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
    }

    /**
     * Method runs string assertions
     *
     * @param array $asserts
     *            asserts
     * @param string $content
     *            content to assert
     */
    protected function runAssertions(array $asserts, string $content): void
    {
        foreach ($asserts as $assert) {
            $this->assertStringContainsString($assert, $content);
        }
    }

    /**
     * Data provider for the testSimpleListingForm
     *
     * @return array test data
     */
    public function simpleListingFormDataProvider(): array
    {
        return [
            // #0, no records
            [
                [],
                [
                    'class="no-items-title"',
                    '{action-message}',
                    'Ни одной записи не найдено',
                    'Some list title'
                ]
            ],
            // #1, no records
            [
                $this->getRecords(),
                [
                    '>1<',
                    '>2<',
                    '{action-message}',
                    'transformed!'
                ]
            ]
        ];
    }

    /**
     * Testing listing form
     *
     * @param array $records
     *            records to display
     * @param array $asserts
     *            asserts
     * @dataProvider simpleListingFormDataProvider
     */
    public function testSimpleListingForm(array $records, array $asserts): void
    {
        // setup
        $listBuilder = new ListBuilder\Simple($this->getFields(), new FakeAdapter($records));
        $listBuilder->listTitle = 'Some list title';
        $listBuilder->setRecordTransformer(
            function (object $record): object {
                Functional::setField($record, 'transformed', 'transformed!');
                return $record;
            });

        // test body
        $content = $listBuilder->listingForm();

        // assertions
        $this->runAssertions($asserts, $content);
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

                    $this->assertStringContainsStrings([
                        '>id<',
                        '>1<',
                        '>2<',
                        'List Title',
                        'List Description'
                    ], $result);
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

                    $this->assertStringContainsStrings(
                        [
                            '>id<',
                            '>1<',
                            '>2<',
                            'Список записей',
                            'Выберите необходимое действие'
                        ],
                        $result);
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
