<?php
namespace Mezon\Gui\Tests\Common;

use Mezon\Gui\ListBuilder;
use Mezon\Gui\Tests\ListBuilderTestsBase;
use Mezon\Gui\Tests\FakeAdapter;
use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;
use Mezon\Gui\ListBuilder\Common;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class UpdateButtonUnitTest extends ListBuilderTestsBase
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
     * Creating list builder
     *
     * @param bool $updateButton
     *            update button
     * @param bool $deleteButton
     *            delete button
     * @return Common list builder
     */
    private function createListBuilder(bool $updateButton, bool $deleteButton): Common
    {
        $listBuilder = new ListBuilder\Common($this->getFields(), new FakeAdapter($this->getRecords()));

        $listBuilder->updateButton = $updateButton;
        $listBuilder->deleteButton = $deleteButton;
        $listBuilder->listTitle = 'Some list title';

        return $listBuilder;
    }

    /**
     * Method compilee list
     *
     * @param bool $updateButton
     *            do we need update button
     * @return string compiled list
     */
    private function createList(bool $updateButton): string
    {
        // setup
        $listBuilder = $this->createListBuilder($updateButton, false);

        // test body
        return $listBuilder->listingForm();
    }

    /**
     * Testing generating update button from setting
     */
    public function testUpdateButtonFromSetting(): void
    {
        // setup and test body
        $content = $this->createList(true);

        // assertions
        $this->assertStringContainsString('<a href="../update/1/"', $content);
    }

    /**
     * Testing generating no update button from setting
     */
    public function testNoUpdateButtonFromSetting(): void
    {
        // setup and test body
        $content = $this->createList(false);

        // assertions
        $this->assertStringNotContainsString('<a href="../update/1/"', $content);
    }

    /**
     * Testing generating update and delete buttons from setting
     */
    public function testUpdateAndDeleteButtonFromSetting(): void
    {
        // setup
        $listBuilder = $this->createListBuilder(true, true);

        // test body
        $content = $listBuilder->listingForm();

        // assertions
        $this->assertStringContainsString('<a href="../update/1/"', $content);
        $this->assertStringContainsString('<a href="../delete/1/"', $content);
    }

    /**
     * Testing generating update button from get parameter
     */
    public function testUpdateButtonFromGetParameter(): void
    {
        // setup and test body
        $_GET['update-button'] = 1;
        $content = $this->createList(false);

        // assertions
        $this->assertStringContainsString('<a href="../update/1/"', $content);
    }
}
