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
        return json_decode(file_get_contents(__DIR__ . '/conf/' . $name . '.json'), true);
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
        $this->assertStringContainsString('<div class="page-title">', $content, 'No form title was found');
        $this->assertStringContainsString('<form', $content, 'No form tag was found');
        $this->assertStringContainsString('<textarea', $content, 'No textarea tag was found');
        $this->assertStringContainsString('<input', $content, 'No input tag was found');
        $this->assertStringContainsString('<select', $content, 'No select tag was found');
        $this->assertStringContainsString('<option', $content, 'No option tag was found');
        $this->assertStringContainsString('type="file"', $content, 'No file field was found');
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
        $this->assertStringContainsString('<form', $content, 'No form tag was found');
        $this->assertStringContainsString('<textarea', $content, 'No textarea tag was found');
        $this->assertStringContainsString('<input', $content, 'No input tag was found');
        $this->assertStringContainsString('<select', $content, 'No select tag was found');
        $this->assertStringContainsString('<option', $content, 'No option tag was found');
        $this->assertStringContainsString('type="file"', $content, 'No file field was found');

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

        $this->assertStringNotContainsString('<div class="page-title">', $content, 'No form title was found');
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

        $this->assertStringContainsString('<div class="page-title">', $content, 'No form title was found');
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
        $this->assertStringNotContainsStringIgnoringCase('<div class="page-title"', $content, 'Form title was found');
    }
}
