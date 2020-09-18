<?php
namespace Mezon\Gui\Field\Tests;

class ListBuilderUnitTest extends \PHPUnit\Framework\TestCase
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
        $listBuilder = new \Mezon\Gui\ListBuilder\ListBuilder($this->getFields(), new FakeAdapter());

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
                [
                    [
                        'id' => 1,
                    ],
                    [
                        'id' => 2,
                    ]
                ],
                [
                    '>id<',
                    '>1<',
                    '>2<'
                ]
            ],
            [
                1,
                [
                    [
                        'id' => 1,
                    ],
                    [
                        'id' => 2,
                    ]
                ],
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
                    'class="no-items-title"',
                    '../create/'
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
        $listBuilder = new \Mezon\Gui\ListBuilder\ListBuilder($this->getFields(), new FakeAdapter($records));

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
            ],
            [
                [
                    [
                        'id' => 1,
                    ],
                    [
                        'id' => 2,
                    ]
                ],
                [
                    '>id<',
                    '>1<',
                    '>2<'
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
        $listBuilder = new \Mezon\Gui\ListBuilder\ListBuilder($this->getFields(), new FakeAdapter($records));

        // test body
        $content = $listBuilder->simpleListingForm();

        // assertions
        $this->runAssertions($asserts, $content);
    }
}
