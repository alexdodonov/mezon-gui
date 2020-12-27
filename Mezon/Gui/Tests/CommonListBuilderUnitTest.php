<?php
namespace Mezon\Gui\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Gui\ListBuilder\Common as CommonListBuilder;

class CommonListBuilderUnitTest extends TestCase
{

    /**
     * Method returns list of fields
     *
     * @return array Fields algorithms object
     */
    protected function getFields(): array
    {
        return [
            'id',
            'domain_id',
            'title'
        ];
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
     * Testing constructor
     */
    public function testConstructorValid(): void
    {
        // setup and test body
        $listBuilder = new CommonListBuilder($this->getFields(), new FakeAdapter());

        // assertions
        $this->assertIsArray($listBuilder->getFields(), 'Invalid fields list type');
    }

    /**
     * Method returns testing records
     *
     * @return array testing records
     */
    private function getRecords(): array
    {
        return [
            [
                'id' => 1,
            ],
            [
                'id' => 2,
            ]
        ];
    }

    /**
     * Combining substrings to assert
     *
     * @param array $specificSubstrings
     *            specific substrings
     * @return array total list of substrings
     */
    private function commonSubstring(array $specificSubstrings): array
    {
        return array_merge([
            '{action-message}'
        ], $specificSubstrings);
    }

    /**
     * Data provider for the testListingForm
     *
     * @return array test data
     */
    public function listingFormDataProvider(): array
    {
        return [
            [
                0,
                $this->getRecords(),
                $this->commonSubstring([
                    '>id<',
                    '>1<',
                    '>2<'
                ])
            ],
            [
                1,
                $this->getRecords(),
                $this->commonSubstring([
                    '>id<',
                    '>1<',
                    '>2<',
                    '/create-endpoint/'
                ])
            ],
            [
                0,
                [],
                $this->commonSubstring([
                    'class="no-items-title"',
                    '../create/',
                ])
            ]
        ];
    }

    /**
     * Testing listing form
     *
     * @param int $createButton
     *            do we need to show create button
     * @param array $records
     *            list of records to be displayed
     * @param array $asserts
     *            asserts
     * @dataProvider listingFormDataProvider
     */
    public function testListingForm(int $createButton, array $records, array $asserts): void
    {
        // setup
        $_GET['create-page-endpoint'] = $createButton ? '/create-endpoint/' : null;
        $_GET['create-button'] = $createButton;
        $listBuilder = new CommonListBuilder($this->getFields(), new FakeAdapter($records));

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
        $setup = function (): object {
            // setup method
            $listBuilder = new CommonListBuilder($this->getFields(), new FakeAdapter($this->getRecords()));

            $listBuilder->setCustomActions('!{id}!');

            return $listBuilder;
        };

        $assert = function ($result): void {
            // asserting method
            $this->assertStringNotContainsString('!1!', $result);
            $this->assertStringNotContainsString('!2!', $result);
        };

        $headerData = [
            'id' => [
                'title' => 'Some id field'
            ]
        ];

        return [
            // #0, listingForm
            [
                $setup,
                function ($result): void {
                    // asserting method
                    $this->assertStringContainsStrings([
                        '!1!',
                        '!2!'
                    ], $result);
                }
            ],
            // #1, listingForm, no custom buttons
            [
                function (): object {
                    // setup method
                    return new CommonListBuilder($this->getFields(), new FakeAdapter($this->getRecords()));
                },
                $assert
            ],
            // #2, listingForm, no custom buttons
            [
                function () use ($headerData): object {
                    // setup method
                    return new CommonListBuilder($headerData, new FakeAdapter($this->getRecords()));
                },
                $assert
            ],
            // #3, listingForm, no custom buttons
            [
                function () use ($headerData): object {
                    // setup method
                    return new CommonListBuilder($headerData, new FakeAdapter($this->getRecords()));
                },
                function (string $result) use ($assert) {
                    $assert($result);

                    $this->assertStringContainsStrings([
                        'Some id field',
                        '>1<',
                        '>2<'
                    ], $result);
                }
            ],
            // #4, listingForm, default buttons
            [
                function (): object {
                    // setup method
                    $_GET['update-button'] = 1;
                    $_GET['create-button'] = 1;
                    return new CommonListBuilder($this->getFields(), new FakeAdapter($this->getRecords()));
                },
                function (string $result) use ($assert) {
                    $assert($result);

                    $this->assertStringContainsStrings([
                        '>id<',
                        '>1<',
                        '>2<'
                    ], $result);
                }
            ],
            // #5, listingForm, custom title and description
            [
                function (): object {
                    // setup method
                    $listBuilder = new CommonListBuilder($this->getFields(), new FakeAdapter($this->getRecords()));
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
            // #6, listingForm, default title and description
            [
                function (): object {
                    // setup method
                    return new CommonListBuilder($this->getFields(), new FakeAdapter($this->getRecords()));
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
