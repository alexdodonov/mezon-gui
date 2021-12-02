<?php
namespace Mezon\Gui\Tests\Common;

use Mezon\Gui\ListBuilder;
use Mezon\Gui\Tests\ListBuilderTestsBase;
use Mezon\Gui\Tests\FakeAdapter;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CreateButtonUnitTest extends ListBuilderTestsBase
{

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
        $this->assertStringContainsString("/create-endpoint-from-setting/", $content);
    }
}
