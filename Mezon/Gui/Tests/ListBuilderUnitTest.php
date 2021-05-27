<?php
namespace Mezon\Gui\Tests;

use Mezon\Gui\ListBuilder\ListBuilder;

class ListBuilderUnitTest extends ListBuilderTestsBase
{

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
        $listBuilder = new ListBuilder($this->getFields(), new FakeAdapter());

        // assertions
        $this->assertIsArray($listBuilder->getFields(), 'Invalid fields list type');
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
                [
                    '>id<',
                    '>1<',
                    '>2<'
                ]
            ],
            [
                1,
                $this->getRecords(),
                [
                    '>id<',
                    '>1<',
                    '>2<',
                    '/create-endpoint/'
                ]
            ],
            [
                0,
                [],
                [
                    'class="no-items-title"'
                ]
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
        $listBuilder = new ListBuilder($this->getFields(), new FakeAdapter($records));

        // test body
        $content = $listBuilder->listingForm();

        // assertions
        $this->runAssertions($asserts, $content);
    }

    /**
     * Data provider for the testSimpleListingForm
     *
     * @return array test data
     */
    public function simpleListingFormDataProvider(): array
    {
        return [
            [
                [],
                [
                    'class="no-items-title"'
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
        $_GET['update-button'] = 1;
        $_GET['create-button'] = 1;
        $listBuilder = new ListBuilder($this->getFields(), new FakeAdapter($records));

        // test body
        $content = $listBuilder->simpleListingForm();

        // assertions
        $this->runAssertions($asserts, $content);
    }

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function customActionsDataProvider(): array
    {
        $setup = function (): object {
            // setup method
            $listBuilder = new ListBuilder($this->getFields(), new FakeAdapter($this->getRecords()));

            $listBuilder->setCustomActions('!{id}!');

            return $listBuilder;
        };

        $assert = function ($result): void {
            // asserting method
            $this->assertStringNotContainsString('!1!', $result);
            $this->assertStringNotContainsString('!2!', $result);
        };

        return [
            // #0, simpleListingForm
            [
                $setup,
                $assert,
                'simpleListingForm'
            ],
            // #1, listingForm
            [
                $setup,
                function ($result): void {
                    // asserting method
                    $this->assertStringContainsString('!1!', $result);
                    $this->assertStringContainsString('!2!', $result);
                },
                'listingForm'
            ],
            // #2, listingForm, no custom buttons
            [
                function (): object {
                    // setup method
                    return new ListBuilder($this->getFields(), new FakeAdapter($this->getRecords()));
                },
                $assert,
                'listingForm'
            ],
            // #3, listingForm, no custom buttons
            [
                function (): object {
                    // setup method
                    return new ListBuilder([
                        'id' => [
                            'title' => 'Some id field'
                        ]
                    ], new FakeAdapter($this->getRecords()));
                },
                $assert,
                'listingForm'
            ],
            // #4, listingForm, no custom buttons
            [
                function (): object {
                    // setup method
                    return new ListBuilder([
                        'id' => [
                            'title' => 'Some id field'
                        ]
                    ], new FakeAdapter($this->getRecords()));
                },
                function (string $result) use ($assert) {
                    $assert($result);

                    $this->assertStringContainsString('Some id field', $result);
                    $this->assertStringContainsString('>1<', $result);
                    $this->assertStringContainsString('>2<', $result);
                },
                'listingForm'
            ],
            // #5, simpleListingForm, no custom buttons
            [
                function (): object {
                    // setup method
                    return new ListBuilder([
                        'title' => [
                            'title' => 'Title field'
                        ]
                    ], new FakeAdapter($this->getRecords()));
                },
                function (string $result) use ($assert) {
                    $assert($result);

                    $this->assertStringContainsString('Title field', $result);
                },
                'simpleListingForm'
            ],
            // #6, listingForm, default buttons
            [
                function (): object {
                    // setup method
                    $_GET['update-button'] = 1;
                    $_GET['create-button'] = 1;
                    return new ListBuilder($this->getFields(), new FakeAdapter($this->getRecords()));
                },
                function (string $result) use ($assert) {
                    $assert($result);

                    $this->assertStringContainsString('>id<', $result);
                    $this->assertStringContainsString('>1<', $result);
                    $this->assertStringContainsString('>2<', $result);
                },
                'simpleListingForm'
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
     * @paran string $method method to be called
     * @dataProvider customActionsDataProvider
     */
    public function testCustomActions(callable $setup, callable $assertions, string $method): void
    {
        // setup
        $obj = $setup();

        // test body
        $result = $obj->$method();

        // assertions
        $assertions($result);
    }
}
