<?php

require_once 'EC/Cache/Backend/LibmemcachedMulti.php';
require_once 'PHPUnit/Framework/TestCase.php';

class EC_Cache_Backend_LibmemcachedMultiTest extends PHPUnit_Framework_TestCase
{
    public function testLoadMulti()
    {
        $mock = $this->getMock('EC_Cache_Backend_LibmemcachedMulti', array('_getMemcached'), array(), '', false);
        $cache = $this->getMock('stdClass', array('getMulti'));
        $cache->expects($this->once())
              ->method('getMulti')
              ->will($this->returnValue(array('foo' => array('bar', 12345, 0))));
        $mock->expects($this->once())
             ->method('_getMemcached')
             ->will($this->returnValue($cache));
        $this->assertSame(array('foo' => 'bar'), $mock->loadMulti(array('foo')));
    }

    public function testSaveMultiSuccess()
    {
        $mock = $this->getMock('EC_Cache_Backend_LibmemcachedMulti', array('_getMemcached'), array(), '', false);
        $cache = $this->getMock('stdClass', array('setMulti'));
        $cache->expects($this->once())
              ->method('setMulti')
              ->will($this->returnCallback(array($this, 'saveMultiSuccessCallback')));
        $mock->expects($this->once())
             ->method('_getMemcached')
             ->will($this->returnValue($cache));
        $this->assertTrue($mock->saveMulti(array('foo' => 'bar'), 1000));
    }

    public function saveMultiSuccessCallback($items, $lifetime)
    {
        $this->assertSame('bar', $items['foo'][0]);
        $this->assertTrue(is_numeric($items['foo'][1]));
        $this->assertSame(1000, $items['foo'][2]);
        $this->assertSame(1000, $lifetime);
        return true;
    }

    public function testSaveMultiFail()
    {
        $mock = $this->getMock('EC_Cache_Backend_LibmemcachedMulti', array('_getMemcached', '_log'), array(), '', false);
        $cache = $this->getMock('stdClass', array('setMulti', 'getResultCode', 'getResultMessage'));
        $cache->expects($this->once())
              ->method('setMulti')
              ->will($this->returnValue(false));
        $cache->expects($this->once())
              ->method('getResultCode')
              ->will($this->returnValue(-1));
        $cache->expects($this->once())
              ->method('getResultMessage')
              ->will($this->returnValue('foobar'));
        $mock->expects($this->once())
             ->method('_getMemcached')
             ->will($this->returnValue($cache));
        $mock->expects($this->once())
             ->method('_log')
             ->will($this->returnCallback(array($this, 'saveMultiFailCallback')));
        $this->assertFalse($mock->saveMulti(array('foo' => 'bar'), 1000));
    }

    public function saveMultiFailCallback($message)
    {
        $this->assertSame('Memcached::set() failed: [-1] foobar', $message);
    }
}
