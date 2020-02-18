<?php

define('ID_FIELD_NAME', 'id');
define('TITLE_FIELD_NAME', 'title');
define('USER_ID_FIELD_NAME', 'user_id');
define('FIELDS_FIELD_NAME', 'fields');
define('DISABLED_FIELD_NAME', 'disabled');
define('STRING_TYPE_NAME', 'string');
define('INTEGER_TYPE_NAME', 'integer');
define('DATE_TYPE_NAME', 'date');
define('EXTERNAL_TYPE_NAME', 'external');

class FieldsAlgorithmsUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Post test processing
     */
    public function tearDown(): void
    {
        unset($_GET[FIELDS_FIELD_NAME]);
    }

    /**
     * Method creates testing data
     *
     * @return array Testing data
     */
    protected function getFields1(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/conf/setup.json'), true);
    }

    /**
     * Method creates testing data
     *
     * @return array Testing data
     */
    protected function getFields2(): array
    {
        return [
            ID_FIELD_NAME => [
                'type' => INTEGER_TYPE_NAME,
                DISABLED_FIELD_NAME => 1
            ],
            TITLE_FIELD_NAME => [
                'type' => STRING_TYPE_NAME,
                'required' => 1
            ]
        ];
    }

    /**
     * Testing invalid construction
     */
    public function testConstructor()
    {
        // setup and test body
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');

        // assertions
        $this->assertEquals('entity', $fieldsAlgorithms->getEntityName(), 'EntityName was not set');
        $this->assertTrue($fieldsAlgorithms->hasCustomFields(), 'Data was not loaded');
    }

    /**
     * Testing hasCustomFields
     */
    public function testHasNotCustomFields()
    {
        // setup and test body
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields2(), 'entity');

        // assertions
        $_GET[FIELDS_FIELD_NAME] = TITLE_FIELD_NAME;
        $this->assertFalse($fieldsAlgorithms->hasCustomFields(), 'Custom fields are not in the model');
    }

    /**
     * Testing hasCustomFields
     */
    public function testHasCustomFields()
    {
        // setup and test body
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');

        // assertions
        $this->assertTrue($fieldsAlgorithms->hasCustomFields(), 'Custom fields are in the model');
    }

    /**
     * Testing getTypedValue
     */
    public function testGetTypedValue()
    {
        // setup and test body
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');

        // assertions int
        $this->assertEquals(
            1,
            $fieldsAlgorithms->getTypedValue(INTEGER_TYPE_NAME, '1'),
            'Type was not casted properly for integer');
        $this->assertTrue(
            is_int($fieldsAlgorithms->getTypedValue(INTEGER_TYPE_NAME, '1')),
            'Type was not casted properly for integer');

        // assertions string
        $this->assertEquals(
            '1',
            $fieldsAlgorithms->getTypedValue(STRING_TYPE_NAME, '1'),
            'Type was not casted properly for string');
        $this->assertTrue(
            is_string($fieldsAlgorithms->getTypedValue(STRING_TYPE_NAME, '1')),
            'Return type is not correct');
        $this->assertEquals(
            '&amp;',
            $fieldsAlgorithms->getTypedValue(STRING_TYPE_NAME, '&'),
            'Type was not casted properly for string');
        $this->assertEquals(
            '&quot;&quot;',
            $fieldsAlgorithms->getTypedValue(STRING_TYPE_NAME, '""'),
            'Default brunch for string is not working');

        // assertions date
        $this->assertEquals(
            '2019-01-01',
            $fieldsAlgorithms->getTypedValue(DATE_TYPE_NAME, '2019-01-01'),
            'Type was not casted properly for date');
        $this->assertEquals(
            '',
            $fieldsAlgorithms->getTypedValue(DATE_TYPE_NAME, '""'),
            'Default date for string is not working');

        // assertions file
        $this->assertContains('value', $fieldsAlgorithms->getTypedValue('file', [
            'value'
        ], false));
        $this->assertFileExists(
            $path = $fieldsAlgorithms->getTypedValue('file', [
                'name' => 'test.txt',
                'file' => '1234'
            ], true),
            'File was not saved');
        unlink($path);

        // assertions external
        $typedValue = $fieldsAlgorithms->getTypedValue(EXTERNAL_TYPE_NAME, [
            '1',
            '2'
        ]);
        $this->assertContains(1, $typedValue);
        $this->assertContains(2, $typedValue);
        $this->assertCount(2, $typedValue);

        // assertion unexisting
        $this->expectException(Exception::class);
        $fieldsAlgorithms->getTypedValue('unexisting', '1');
    }

    /**
     * Test validateFieldExistance method
     */
    public function testValidateFieldExistance()
    {
        // setup and test body
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');

        // test body and assertions
        $this->expectException(Exception::class);
        $fieldsAlgorithms->validateFieldExistance('unexisting-field');

        $this->expectException(Exception::class);
        $fieldsAlgorithms->validateFieldExistance('id');
    }

    /**
     * Test getSecureValue method
     */
    public function testGetSecureValue()
    {
        // setup and test body
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');

        // test body and assertions
        $id = $fieldsAlgorithms->getSecureValue('id', '1');

        // assertions
        $this->assertIsInt($id, 'Invalid secure processing for integer value');
        $this->assertEquals(1, $id, 'Data loss for integer value');
    }

    /**
     * Test getSecureValues method
     */
    public function testGetSecureValues()
    {
        // setup and test body
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');

        // test body and assertions
        $id = $fieldsAlgorithms->getSecureValues('id', [
            '1',
            '2&'
        ]);

        // assertions
        $this->assertIsInt($id[0], 'Invalid secure processing for integer values');
        $this->assertIsInt($id[1], 'Invalid secure processing for integer values');

        $this->assertEquals(1, $id[0], 'Data loss for integer values');
        $this->assertEquals(2, $id[1], 'Data loss for integer values');
    }

    /**
     * Test getValuesForPrefix method
     */
    public function testGetValuesForPrefix()
    {
        // setup and test body
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');
        $_POST['prefix-id'] = '1';
        $_POST['prefix-title'] = 'some string';

        // test body and assertions
        $result = $fieldsAlgorithms->getValuesForPrefix('prefix-');

        // assertions
        $this->assertIsInt($result['id'], 'Invalid secure processing for integer prefix');
        $this->assertIsString($result[TITLE_FIELD_NAME], 'Invalid secure processing for string prefix');

        $this->assertEquals(1, $result['id'], 'Data loss for integer preix');
        $this->assertEquals('some string', $result[TITLE_FIELD_NAME], 'Data loss for string preix');
    }

    /**
     * Testing 'removeField' method
     */
    public function testRemoveField()
    {
        // setup
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');

        // test body
        $fieldsAlgorithms->removeField('extensions');

        // assertions
        $this->assertFalse($fieldsAlgorithms->hasCustomFields(), 'Field "extensions" was not removed');
    }

    /**
     * Testing 'fetchCustomField' method for unexisting field
     */
    public function testFetchCustomFieldUnexistingField()
    {
        // setup
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');
        $record = [];

        // test body
        $result = $fieldsAlgorithms->fetchCustomField($record, 'unexisting');

        // assertions
        $this->assertEquals(0, count($result), 'Something was returned, but should not');
    }

    /**
     * Testing 'fetchCustomField' method
     */
    public function testFetchCustomField()
    {
        // setup
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');
        $record = [];
        $_POST['entity' . '-balance'] = '11';

        // test body
        $result = $fieldsAlgorithms->fetchCustomField($record, 'extensions');

        // assertions
        $this->assertEquals(11, $result['balance'], 'Invalid field value');
    }

    /**
     * Testing 'fetchField' method
     */
    public function testFetchField()
    {
        // setup
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');
        $record = [];
        $_POST['entity' . '-id'] = '11';
        $_FILES['entity' . '-avatar'] = [
            'name' => 'test.dat',
            'file' => 'content'
        ];
        $_POST['entity' . '-balance'] = '33';

        // test body
        $fieldsAlgorithms->fetchField($record, 'id');
        $fieldsAlgorithms->fetchField($record, 'avatar');
        $fieldsAlgorithms->fetchField($record, 'extensions');

        // assertions
        $this->assertEquals(11, $record['id'], 'id was not fetched');
        $avatar = $record['avatar'];
        $this->assertFileExists($avatar, 'File does not exists');
        unlink($avatar);
        $this->assertEquals(33, $record['balance'], 'balance was not fetched');
    }

    /**
     * Testing 'getObject' method
     */
    public function testGetObject()
    {
        // setup
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');

        // test body
        $object = $fieldsAlgorithms->getObject('title');

        // assertions
        $this->assertInstanceOf(\Mezon\Gui\Field\InputText::class, $object);
    }

    /**
     * Testing 'getFieldsNames' method
     */
    public function testGetFieldsNames()
    {
        // setup
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');

        // test body
        $fields = $fieldsAlgorithms->getFieldsNames();

        // assertions
        $this->assertContains('id',$fields);
        $this->assertContains('title',$fields);
        $this->assertContains('user_id',$fields);
        $this->assertContains('label',$fields);
        $this->assertContains('description',$fields);
        $this->assertContains('created',$fields);
        $this->assertContains('avatar',$fields);
        $this->assertContains('parts',$fields);
        $this->assertContains('extensions',$fields);
    }

    /**
     * Testing field compilation
     */
    public function testGetCompiledField(): void
    {
        // setup
        $fieldsAlgorithms = new \Mezon\Gui\FieldsAlgorithms($this->getFields1(), 'entity');

        // test body
        $inputField = $fieldsAlgorithms->getCompiledField('title');
        $textareaField = $fieldsAlgorithms->getCompiledField('description');

        // assertions
        $this->assertStringContainsString('<input ', $inputField);
        $this->assertStringContainsString('<textarea ', $textareaField);
    }
}
