<?php
namespace Mezon\Gui\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Gui\ListBuilder\Simple as SimpleListBuilder;
use Mezon\Router\Router;
use Mezon\Transport\Request;

class SimpleListBuilderUnitTest extends TestCase
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
        $listBuilder = new SimpleListBuilder($this->getFields(), new FakeAdapter());

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
                    '{action-message}'
                ]
            ],
            // #1, no records
            [
                $this->getRecords(),
                [
                    '>1<',
                    '>2<',
                    '{action-message}'
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
        $listBuilder = new SimpleListBuilder($this->getFields(), new FakeAdapter($records));

        // test body
        $content = $listBuilder->listingForm();

        // assertions
        $this->runAssertions($asserts, $content);
    }
}
