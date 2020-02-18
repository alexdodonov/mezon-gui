<?php

define('SESSION_ID', 'session-id');

class FormBuilderUnitTest extends \PHPUnit\Framework\TestCase
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
     * @return \Mezon\Gui\FieldsAlgorithms Fields algorithms object
     */
    protected function getFieldsAlgorithms()
    {
        return new \Mezon\Gui\FieldsAlgorithms($this->getJson('form-builder-setup'), 'entity');
    }

    /**
     * Setting on and off the form title flag.
     *
     * @param bool $flag
     */
    protected function formHeader(bool $flag)
    {
        if (! $flag) {
            $_GET['no-header'] = 1;
        } else {
            unset($_GET['no-header']);
        }
    }

    /**
     * Method returns mock for FormBuilder
     *
     * @return object Mock of the object
     */
    protected function getFormBuilder(bool $hasLayout = true): object
    {
        $formBuilder = $this->getMockBuilder(\Mezon\Gui\FormBuilder::class)
            ->setMethods([
            'get_external_records'
        ])
            ->setConstructorArgs(
            [
                $this->getFieldsAlgorithms(),
                SESSION_ID,
                'test-record',
                $hasLayout ? $this->getJson('layout') : []
            ])
            ->getMock();

        $formBuilder->method('get_external_records')->willReturn([
            [
                'id' => 1,
                'title' => "Some title"
            ]
        ]);

        return $formBuilder;
    }

    /**
     * Testing creation form
     */
    public function testCreationForm(): void
    {
        // setup
        $formBuilder = $this->getFormBuilder();

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
     * Testing creation form
     */
    public function testUpdatingForm(): void
    {
        // setup
        $formBuilder = $this->getFormBuilder();

        $this->formHeader(true);

        // test body
        $content = $formBuilder->updatingForm('session-id', [
            'id' => '23'
        ]);

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
     * Testing constructor with no form title
     */
    public function testConstructorNoFormTitle(): void
    {
        // setup
        $_GET['form-width'] = 7;
        $formBuilder = $this->getFormBuilder(false);

        $this->formHeader(false);

        // test body
        $content = $formBuilder->creationForm();

        // assertions
        $this->assertStringNotContainsStringIgnoringCase('<div class="page-title"', $content, 'Form title was found');
    }
}
