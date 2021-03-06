<?php

use Cloudflare\API\Adapter\Adapter;
use Cloudflare\API\Endpoints\WorkersKV;

class WorkersKVTest extends TestCase
{
    public function testCreateNamespace()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/createWorkersKVNamespace.json');

        $mock = $this->getMockBuilder(Adapter::class)->getMock();

        $mock->method('post')->willReturn($response);

        $mock->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('accounts/023e105f4ecef8ad9ca31a8372d0c353/storage/kv/namespaces'),
                $this->equalTo(['title' => "Foo"])
            );

        $worker = new WorkersKV($mock);
        $result = $worker->createNamespace("023e105f4ecef8ad9ca31a8372d0c353", "Foo");
        $this->assertObjectHasAttribute('id', $result);
        $this->assertEquals('6b23666a511e428aa9da1bad45a0c81f', $result->id);
    }

    public function testGetNamespaces()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getWorkersKVNamespaces.json');

        $mock = $this->getMockBuilder(Adapter::class)->getMock();

        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('accounts/023e105f4ecef8ad9ca31a8372d0c353/storage/kv/namespaces')
            );

        $worker = new WorkersKV($mock);
        $result = $worker->getNameSpaces("023e105f4ecef8ad9ca31a8372d0c353");
        $this->assertCount(1, $result);
    }

    public function testGetAllNamespacesKeysAndValues()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getWorkersKVListNamespaceKeys.json');
        $responseTwo = $this->getPsr7JsonResponseForFixture('Endpoints/getWorkersKVNamespacesKeyValue.json');
        $mock = $this->getMockBuilder(Adapter::class)->getMock();
        $mock->method('get')->willReturn($response, $responseTwo);
        $worker = new WorkersKV($mock);
        $result = $worker->getAllKeysAndValuesForNamespace("023e105f4ecef8ad9ca31a8372d0c353", "0f2ac74b498b48028cb68387c421e279");
        $this->assertCount(1, $result);
        $this->assertObjectHasAttribute("value", $result[0]);
        $this->assertEquals("Some Value", $result[0]->value);
    }

    public function testListNamespaceKeys()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getWorkersKVListNamespaceKeys.json');

        $mock = $this->getMockBuilder(Adapter::class)->getMock();

        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('accounts/023e105f4ecef8ad9ca31a8372d0c353/storage/kv/namespaces/0f2ac74b498b48028cb68387c421e279/keys')
            );

        $worker = new WorkersKV($mock);
        $result = $worker->listNamespaceKeys("023e105f4ecef8ad9ca31a8372d0c353", "0f2ac74b498b48028cb68387c421e279");
        $this->assertCount(1, $result);
    }

    public function testGetListOfNamespaces()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getWorkersKVListNamespaceKeys.json');

        $mock = $this->getMockBuilder(Adapter::class)->getMock();

        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('accounts/023e105f4ecef8ad9ca31a8372d0c353/storage/kv/namespaces/0f2ac74b498b48028cb68387c421e279/keys')
            );

        $worker = new WorkersKV($mock);
        $result = $worker->listNamespaceKeys("023e105f4ecef8ad9ca31a8372d0c353", "0f2ac74b498b48028cb68387c421e279");
        $this->assertCount(1, $result);
    }

    public function testWriteMultipleKeyValuePairs()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/putWorkersKVWriteMultipleKeyValuePairs.json');

        $mock = $this->getMockBuilder(Adapter::class)->getMock();

        $mock->method('put')->willReturn($response);

        $bulkKeys = [
            [
                "key" => "Foo", "value" => "bar"
            ],
            [
                "key" => "Foo2", "value" => "bar2"
            ]
        ];
        $mock->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo('accounts/023e105f4ecef8ad9ca31a8372d0c353/storage/kv/namespaces/0f2ac74b498b48028cb68387c421e279/bulk'),
                $this->equalTo(
                    $bulkKeys
                )
            );

        $worker = new WorkersKV($mock);
        $result = $worker->writeMultipleKeyValuePairs("023e105f4ecef8ad9ca31a8372d0c353", "0f2ac74b498b48028cb68387c421e279", $bulkKeys);
        $this->assertTrue($result);
    }

    public function testGetReadKeyValuePair()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getWorkersKVNamespacesKeyValue.json');

        $mock = $this->getMockBuilder(Adapter::class)->getMock();

        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('accounts/023e105f4ecef8ad9ca31a8372d0c353/storage/kv/namespaces/0f2ac74b498b48028cb68387c421e279/values/Foo')
            );

        $worker = new WorkersKV($mock);
        $result = $worker->getReadKeyValuePair("023e105f4ecef8ad9ca31a8372d0c353", "0f2ac74b498b48028cb68387c421e279", "Foo");
        $this->assertEquals("Some Value", $result);
    }

    public function testDeleteKeyValuePair ()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/deleteKeyValuePair.json');

        $mock = $this->getMockBuilder(Adapter::class)->getMock();

        $mock->method('delete')->willReturn($response);

        $mock->expects($this->once())
            ->method('delete')
            ->with(
                $this->equalTo('accounts/023e105f4ecef8ad9ca31a8372d0c353/storage/kv/namespaces/0f2ac74b498b48028cb68387c421e279/values'),
                ['Foo']
            );

            $worker = new WorkersKV($mock);
            $result = $worker->deleteKeyValuePair("023e105f4ecef8ad9ca31a8372d0c353", "0f2ac74b498b48028cb68387c421e279", 'Foo');
            $this->assertTrue($result);
    }
}
