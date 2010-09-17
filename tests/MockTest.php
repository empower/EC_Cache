<?php

require_once 'PHPUnit/Framework.php';
require_once 'Zend/Cache.php';

class Zend_Cache_Backend_MockTest extends PHPUnit_Framework_TestCase
{
    protected $_cache = null;

    public function setUp()
    {
        $frontendOptions = array(
            'cache_id_prefix' => null,
            'automatic_serialization' => true,
        );

        $this->_cache = Zend_Cache::factory('Core', 'Mock',
                $frontendOptions, array());
    }

    public function testRemove()
    {
        $this->_cache->save('value', 'key');
        $this->assertSame('value', $this->_cache->load('key'));
        $this->_cache->remove('key');
        $this->assertSame(false, $this->_cache->load('key'));
    }

    public function testClean()
    {
        $this->_cache->save('value', 'key');
        $this->_cache->save('value2', 'key2');
        $this->_cache->clean();
        $this->assertSame(false, $this->_cache->load('key'));
        $this->assertSame(false, $this->_cache->load('key2'));
    }

    public function testGetIds()
    {
        $this->assertSame(array(), $this->_cache->getIds());
        $this->_cache->save('value', 'key');
        $this->_cache->save('value2', 'key2');
        $ids = $this->_cache->getIds();
        sort($ids);
        $this->assertSame(array('key', 'key2'), $ids);
    }

    public function testTagsNotSupported()
    {
        $this->setExpectedException('Zend_Cache_Exception');
        $this->_cache->getTags();
    }

    public function testTagsNotSupported2()
    {
        $this->setExpectedException('Zend_Cache_Exception');
        $this->_cache->getIdsMatchingTags();
    }

    public function testTagsNotSupported3()
    {
        $this->setExpectedException('Zend_Cache_Exception');
        $this->_cache->getIdsNotMatchingTags();
    }

    public function testTagsNotSupported4()
    {
        $this->setExpectedException('Zend_Cache_Exception');
        $this->_cache->getIdsMatchingAnyTags();
    }

    public function testNotImplemented()
    {
        $this->assertSame(0, $this->_cache->getFillingPercentage());
        $this->assertSame(false, $this->_cache->getMetadatas('foo'));
        $this->assertSame(false, $this->_cache->touch('foo', 'bar'));
    }

}
