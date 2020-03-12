<?php

class RemoteFieldUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructorNoSessionId()
    {
        // setup
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Session id is not defined');

        // test body and assertions
        $field = new \Mezon\Gui\Field\RemoteField([
            'name' =>'name',
            'text' => 'text',
            'type' => 'external'
        ]);
    }

    /**
     * Testing constructor
     */
    public function testConstructorNoRemoteSource()
    {
        // setup
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Remote source of records is not defined');

        // test body and assertions
        $field = new \Mezon\Gui\Field\RemoteField([
            'name' =>'name',
            'text' => 'text',
            'session-id' => 'sid',
            'type' => 'external'
        ]);
    }
}
