<?php
namespace Mezon\Gui\FormBuilder\Tests;

use Mezon\Gui\FormBuilder\FormBuilder;
use Mezon\Gui\FieldsAlgorithms;
use PHPUnit\Framework\TestCase;
define('SESSION_ID', 'session-id');

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FormBuilderUnitTest extends TestCase
{

    /**
     * Method returns testing data
     *
     * @param string $name
     *            File name
     * @return array Testing data
     */
    protected function getJson(string $name): array
    {
        return json_decode(file_get_contents(__DIR__ . '/Conf/' . $name . '.json'), true);
    }

    /**
     * Method constructs FieldsAlgorithms object
     *
     * @return FieldsAlgorithms Fields algorithms object
     */
    protected function getFieldsAlgorithms()
    {
        return new FieldsAlgorithms($this->getJson('form-builder-setup'), 'entity');
    }

    /**
     * Setting on and off the form title flag.
     *
     * @param bool $flag
     */
    private function formHeader(bool $flag): void
    {
        if (! $flag) {
            $_GET['no-header'] = 1;
        } else {
            unset($_GET['no-header']);
        }
    }

    /**
     * Testing data for creation form tests
     *
     * @return array testing data
     */
    public function creationFormWidthDataProvider(): array
    {
        return [
            [
                $this->getJson('layout')
            ],
            [
                []
            ]
        ];
    }

    /**
     * Testing creation form
     *
     * @param array $layout
     *            layout config
     * @dataProvider creationFormWidthDataProvider
     * @psalm-suppress RedundantCondition
     */
    public function testCreationFormWith(array $layout): void
    {
        // setup
        if (isset($_GET)) {
            unset($_GET['form-width']);
        }
        $formBuilder = new FormBuilder($this->getFieldsAlgorithms(), SESSION_ID, 'test-record', $layout);

        $this->formHeader(true);

        // test body
        $content = $formBuilder->creationForm();

        // assertions
        $this->assertStringContainsString('<div class="page-title">', $content);
        $this->assertStringContainsString('<form', $content);
        $this->assertStringContainsString('<textarea', $content);
        $this->assertStringContainsString('<input', $content);
        $this->assertStringContainsString('<select', $content);
        $this->assertStringContainsString('<option', $content);
        $this->assertStringContainsString('type="file"', $content);
    }

    /**
     * Common part of the tests testUpdatingFormWithNoHeader and testUpdatingFormWithHeader
     *
     * @return string form content
     */
    private function updatingFormTestCommonPart(): string
    {
        // setup
        $formBuilder = new FormBuilder($this->getFieldsAlgorithms(), SESSION_ID, 'test-record', $this->getJson('layout'));

        // test body
        $content = $formBuilder->updatingForm('session-id', [
            'id' => '23'
        ]);

        // assertions
        $this->assertStringContainsString('<form', $content);
        $this->assertStringContainsString('<textarea', $content);
        $this->assertStringContainsString('<input', $content);
        $this->assertStringContainsString('<select', $content);
        $this->assertStringContainsString('<option', $content);
        $this->assertStringContainsString('type="file"', $content);

        return $content;
    }

    /**
     * Testing updating form with no header
     */
    public function testUpdatingFormWithNoHeader(): void
    {
        // setup
        $_GET['no-header'] = 1;

        $content = $this->updatingFormTestCommonPart();

        $this->assertStringNotContainsString('<div class="page-title">', $content);
    }

    /**
     * Testing updating form with header
     */
    public function testUpdatingFormWithHeader(): void
    {
        // setup
        if (isset($_GET['no-header'])) {
            unset($_GET['no-header']);
        }

        $content = $this->updatingFormTestCommonPart();

        $this->assertStringContainsString('<div class="page-title">', $content);
    }

    /**
     * Testing constructor with no form title
     */
    public function testConstructorNoFormTitle(): void
    {
        // setup
        $_GET['form-width'] = 7;
        $formBuilder = new FormBuilder($this->getFieldsAlgorithms(), SESSION_ID, 'test-record', []);

        $this->formHeader(false);

        // test body
        $content = $formBuilder->creationForm();

        // assertions
        $this->assertStringNotContainsStringIgnoringCase('<div class="page-title"', $content);
    }
}
