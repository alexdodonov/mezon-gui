<?php
namespace Mezon\Gui\Tests\Common;

use Mezon\Gui\ListBuilder;
use Mezon\Gui\Tests\ListBuilderTestsBase;
use Mezon\Gui\Tests\FakeAdapter;
use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CreateButtonUnitTest extends ListBuilderTestsBase
{

    /**
     *
     * {@inheritdoc}
     * @see TestCase::setUp()
     * @psalm-suppress RedundantCondition
     */
    protected function setUp(): void
    {
        if (isset($_GET)) {
            $_GET = [];
        }

        Conf::setConfigStringValue('headers/layer', 'mock');
    }

    /**
     * Testing generating create button from setting
     */
    public function testCreateButtonFromSetting(): void
    {
        // setup
        $_GET['create-page-endpoint'] = '/create-endpoint/';
        $_GET['create-button'] = 1;
        $listBuilder = new ListBuilder\Common($this->getFields(), new FakeAdapter($this->getRecords()));
        $listBuilder->createButtonEndpoint = '/create-endpoint-from-setting/';
        $listBuilder->listTitle = 'Some list title';

        // test body
        $content = $listBuilder->listingForm();

        // assertions
        $this->assertStringContainsString('/create-endpoint-from-setting/', $content);
        $this->assertStringNotContainsString('../update/1/', $content);
        $this->assertStringNotContainsString('../delete/1/', $content);
    }
}
