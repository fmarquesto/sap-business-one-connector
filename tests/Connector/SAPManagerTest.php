<?php

namespace Tests\Connector;

use fmarquesto\SapBusinessOneConnector\Connector\SAPBusinessOneConnector;
use fmarquesto\SapBusinessOneConnector\Connector\SAPManager;
use fmarquesto\SapBusinessOneConnector\Repositories\ItemRepository;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class SAPManagerTest extends TestCase
{
    private SAPManager $manager;
    private SAPBusinessOneConnector $connector;
    public function setUp(): void
    {
        $this->manager = new SAPManager($this->connector = \Mockery::spy(SAPBusinessOneConnector::class));
    }

    public function testGetAllItems()
    {
        $items = m::mock(ItemRepository::class);
        $items->shouldReceive('endpoint')->andReturn('Items');
        $items->shouldReceive('selectProperties')->andReturn(['ItemCode']);

        $this->connector->shouldReceive('execute')->withArgs(function($method, $url){
            $this->assertEquals('Items?$select=ItemCode', $url);
            $this->assertEquals('GET', $method);
            return true;
        });
        $res = $this->manager->getAll($items);
    }

    public function testGetOneItemByKey()
    {
        $items = m::mock(ItemRepository::class);
        $items->shouldReceive('endpoint')->andReturn('Items');
        $items->shouldReceive('selectProperties')->andReturn(['ItemCode']);

        $this->connector->shouldReceive('execute')->withArgs(function($method, $url){
            $this->assertEquals('Items(\'key\')?$select=ItemCode', $url);
            $this->assertEquals('GET', $method);
            return true;
        });
        $this->manager->getOneByKey($items, "'key'");
    }

    public function testGetAllByFilter()
    {
        $items = m::mock(ItemRepository::class);
        $items->shouldReceive('endpoint')->andReturn('Items');
        $items->shouldReceive('selectProperties')->andReturn(['ItemCode']);

        $this->connector->shouldReceive('execute')->withArgs(function($method, $url){
            $this->assertEquals('Items?$select=ItemCode&$filter=ItemCode%20eq%20%27key%27', $url);
            $this->assertEquals('GET', $method);
            return true;
        });
        $this->manager->getAllByFilter($items, "ItemCode eq 'key'");
    }

    public function testGetFirstByFilter()
    {
        $items = m::mock(ItemRepository::class);
        $items->shouldReceive('endpoint')->andReturn('Items');
        $items->shouldReceive('selectProperties')->andReturn(['ItemCode']);

        $this->connector->shouldReceive('execute')->withArgs(function($method, $url){
            $this->assertEquals('Items?$select=ItemCode&$filter=ItemCode%20eq%20%27key%27&$top=1', $url);
            $this->assertEquals('GET', $method);
            return true;
        });
        $this->manager->getFirstByFilter($items, "ItemCode eq 'key'");
    }

    public function testCreate()
    {
        $fakeObj = ['ItemCode' => '101', 'ItemName'=> 'Name'];
        $items = m::mock(ItemRepository::class);
        $items->shouldReceive('endpoint')->andReturn('Items');
        $this->connector->shouldReceive('execute')->withArgs(function($method, $url, $data) use($fakeObj){
            $this->assertEquals('Items', $url);
            $this->assertEquals('POST', $method);
            $this->assertEquals($fakeObj, $data);
            return true;
        });
        $this->manager->create($items, $fakeObj);
    }

    public function testUpdate()
    {
        $fakeObj = ['ItemCode' => '101', 'ItemName'=> 'Name'];
        $items = m::mock(ItemRepository::class);
        $items->shouldReceive('endpoint')->andReturn('Items');
        $this->connector->shouldReceive('execute')->withArgs(function($method, $url, $data) use($fakeObj){
            $this->assertEquals("Items('key')", $url);
            $this->assertEquals('PATCH', $method);
            $this->assertEquals($fakeObj, $data);
            return true;
        });
        $this->manager->update($items, "'key'", $fakeObj);
    }

    public function testDelete()
    {
        $fakeObj = ['ItemCode' => '101', 'ItemName'=> 'Name'];
        $items = m::mock(ItemRepository::class);
        $items->shouldReceive('endpoint')->andReturn('Items');
        $this->connector->shouldReceive('execute')->withArgs(function($method, $url) use($fakeObj){
            $this->assertEquals("Items('key')", $url);
            $this->assertEquals('DELETE', $method);
            return true;
        });
        $this->manager->delete($items, "'key'", $fakeObj);
    }

    public function testUpdateBatch()
    {
        $fakeObj = ['ItemCode' => '101', 'ItemName'=> 'Name'];
        $items = m::mock(ItemRepository::class);
        $items->shouldReceive('endpoint')->andReturn('Items');

        $res = $this->manager->updateByBatch($items, $fakeObj);
        $this->assertEmpty($res);
    }

    public function testGetWithCustomSelect()
    {
        $items = m::mock(ItemRepository::class);
        $items->shouldReceive('endpoint')->andReturn('Items');
        $items->shouldReceive('selectProperties')->andReturn([]);
        $items->shouldReceive('defaultSelect')->andReturn(['ItemCode']);

        $this->connector->shouldReceive('execute')->withArgs(function($method, $url){
            $this->assertEquals('Items(\'key\')?$select=ItemCode', $url);
            $this->assertEquals('GET', $method);
            return true;
        });
        $this->manager->getOneByKey($items, "'key'");
    }

}
