<?php

class FakeAdapter implements \Mezon\Gui\ListBuilder\ListBuilderAdapter
{

    /**
     * Records to be returned
     *
     * @var array
     */
    protected $records = [];

    /**
     * Constructor
     *
     * @param array $records
     */
    public function __construct(array $records = [
        [
            'id' => 1,
        ],
        [
            'id' => 2,
        ]
    ])
    {
        $this->records = $records;
    }

    /**
     * Method returns all vailable records
     *
     * @return array all vailable records
     */
    public function all(): array
    {
        return $this->records;
    }

    /**
     * Method returns a subset from vailable records
     *
     * @param array $order
     *            order settings
     * @param int $from
     *            the beginning of the bunch
     * @param int $limit
     *            the size of the batch
     * @return array subset from vailable records
     */
    public function getRecords(array $order, int $from, int $limit): array
    {
        return $this->all();
    }

    /**
     * Record preprocessor
     *
     * @param array $record
     *            record to be preprocessed
     * @return array preprocessed record
     */
    public function preprocessListItem(array $record): array
    {
        return $record;
    }
}

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
                    '>2<'
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
        $_GET['create_button'] = $createButton;
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
        $_GET['update_button'] = 1;
        $_GET['create_button'] = 1;
        $listBuilder = new \Mezon\Gui\ListBuilder\ListBuilder($this->getFields(), new FakeAdapter($records));

        // test body
        $content = $listBuilder->simpleListingForm();

        // assertions
        $this->runAssertions($asserts, $content);
    }
}
