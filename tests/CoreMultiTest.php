<?php

require_once 'EC/Cache/Frontend/CoreMulti.php';
require_once 'PHPUnit/Framework/TestCase.php';

class EC_Cache_Frontend_CoreMultiTest extends PHPUnit_Framework_TestCase
{
    public function testLoadMultiSuccess()
    {
        $mock = $this->getMock('EC_Cache_Frontend_CoreMulti', array('_getBackend', '_log'), array(), '', false);
        $results = array(
            'key1' => serialize(array('foo' => 'bar')),
            'key2' => serialize(array('bar' => 'foo'))
        );

        $backend = $this->getMock('stdClass', array('loadMulti'));
        $backend->expects($this->once())
                ->method('loadMulti')
                ->will($this->returnValue($results));
        $mock->expects($this->once())
             ->method('_getBackend')
             ->will($this->returnValue($backend));
        $mock->expects($this->once())
             ->method('_log');

        $mock->setOption('automatic_serialization', true);

        $values = $mock->loadMulti(array('key1', 'key2'));
        $this->assertSame(array('foo' => 'bar'), $values['key1']);
        $this->assertSame(array('bar' => 'foo'), $values['key2']);
    }

    public function testLoadMultiCachingDisabled()
    {
        $mock = $this->getMock('EC_Cache_Frontend_CoreMulti', array('_getBackend', '_log'), array(), '', false);
        $mock->setOption('caching', false);

        $this->assertSame(array(), $mock->loadMulti(array('key1', 'key2')));
    }

    public function testSaveMultiSuccess()
    {
        $mock = $this->getMock('EC_Cache_Frontend_CoreMulti', array('_getBackend', '_log'), array(), '', false);

        $backend = $this->getMock('stdClass', array('saveMulti'));
        $backend->expects($this->once())
                ->method('saveMulti')
                ->will($this->returnValue(true));
        $mock->expects($this->once())
             ->method('_getBackend')
             ->will($this->returnValue($backend));
        $mock->expects($this->once())
             ->method('_log');

        $mock->setOption('automatic_serialization', true);
        $mock->setOption('ignore_user_abort', true);

        $this->assertTrue($mock->saveMulti(array('key1' => 'foobar', 'key2' => 'barfoo')));
    }

    public function testSaveMultiSuccessNoSerialization()
    {
        $mock = $this->getMock('EC_Cache_Frontend_CoreMulti', array('_getBackend', '_log'), array(), '', false);

        $backend = $this->getMock('stdClass', array('saveMulti'));
        $backend->expects($this->once())
                ->method('saveMulti')
                ->will($this->returnValue(true));
        $mock->expects($this->once())
             ->method('_getBackend')
             ->will($this->returnValue($backend));
        $mock->expects($this->once())
             ->method('_log');

        $mock->setOption('automatic_serialization', false);
        $mock->setOption('ignore_user_abort', true);

        $this->assertTrue($mock->saveMulti(array('key1' => 'foobar', 'key2' => 'barfoo')));
    }

    public function testSaveMultiSerializationFail()
    {
        $mock = $this->getMock('EC_Cache_Frontend_CoreMulti', array('_getBackend', '_log'), array(), '', false);

        $mock->expects($this->exactly(0))
             ->method('_getBackend');
        $mock->expects($this->exactly(0))
             ->method('_log');

        $mock->setOption('automatic_serialization', false);
        $mock->setOption('ignore_user_abort', true);

        $valueOne = new stdClass();
        $valueTwo = new stdClass();
        $this->setExpectedException('Zend_Cache_Exception', 'Datas must be string or set automatic_serialization = true');
        $mock->saveMulti(array('key1' => $valueOne, 'key2' => $valueTwo));
    }

    public function testSaveMultiCachingDisabled()
    {
        $mock = $this->getMock('EC_Cache_Frontend_CoreMulti', array('_getBackend', '_log'), array(), '', false);

        $mock->expects($this->exactly(0))
             ->method('_getBackend');
        $mock->expects($this->exactly(0))
             ->method('_log');

        $mock->setOption('caching', false);

        $this->assertFalse($mock->saveMulti(array('key1' => 'foo', 'key2' => 'bar')));
    }
}
