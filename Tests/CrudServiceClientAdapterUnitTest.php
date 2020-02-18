<?php

class CrudServiceClientAdapterUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing lazy getter
     */
    public function testLazyGetServiceClient()
    {
        // setup
        $adapter = new \Mezon\Gui\ListBuilder\CrudServiceClientAdapter('https://service.example');

        // test body
        $client = $adapter->getClient();

        // assertions
        $this->assertInstanceOf(\Mezon\CrudService\CrudServiceClient::class, $client);
    }
}
