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
class DeleteButtonUnitTest extends ListBuilderTestsBase
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
     * Method compilee list
     *
     * @param bool $deleteButton
     *            do we need delete button
     * @return string compiled list
     */
    private function createList(bool $deleteButton): string
    {
        // setup
        $listBuilder = new ListBuilder\Common($this->getFields(), new FakeAdapter($this->getRecords()));
        $listBuilder->deleteButton = $deleteButton;
        $listBuilder->listTitle = 'Some list title';

        // test body
        return $listBuilder->listingForm();
    }

    /**
     * Testing generating delete button from setting
     */
    public function testDeleteButtonFromSetting(): void
    {
        // setup and test body
        $content = $this->createList(true);

        // assertions
        $this->assertStringContainsString('<a href="../delete/1/"', $content);
    }

    /**
     * Testing generating no delete button from setting
     */
    public function testNoDeleteButtonFromSetting(): void
    {
        // setup and test body
        $content = $this->createList(false);

        // assertions
        $this->assertStringNotContainsString('<a href="../delete/1/"', $content);
    }

    /**
     * Testing generating delete button from get parameter
     */
    public function testDeleteButtonFromGetParameter(): void
    {
        // setup and test body
        $_GET['delete-button'] = 1;
        $content = $this->createList(false);

        // assertions
        $this->assertStringContainsString('<a href="../delete/1/"', $content);
    }
}
