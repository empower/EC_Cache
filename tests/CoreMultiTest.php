<?php

require_once 'EC/Cache/Frontend/CoreMulti.php';
require_once 'PHPUnit/Framework/TestCase.php';

class EC_Cache_Frontend_CoreMultiTest extends PHPUnit_Framework_TestCase
{
    public function testLoadMultiSuccess()
    {
        $cache = Zend_Cache::factory('EC_Cache_Frontend_CoreMulti', 'EC_Cache_Backend_MockMulti', array(), array(), true, true);

        $cache->setOption('automatic_serialization', true);
        $cache->setOption('ignore_user_abort', true);

        $cache->saveMulti(
            array(
                'key1' => array('foo' => 'bar'),
                'key2' => array('bar' => 'foo'),
            )
        );
        $values = $cache->loadMulti(array('key1', 'key2'));
        $this->assertSame(array('foo' => 'bar'), $values['key1']);
        $this->assertSame(array('bar' => 'foo'), $values['key2']);
    }

    public function testSaveMultiFailure()
    {
        $cache = Zend_Cache::factory('EC_Cache_Frontend_CoreMulti', 'EC_Cache_Backend_MockMulti', array(), array(), true, true);

        $cache->setOption('automatic_serialization', false);
        $cache->setOption('ignore_user_abort', true);

        $this->setExpectedException('Zend_Cache_Exception', 'Datas must be string or set automatic_serialization = true');
        $cache->saveMulti(
            array(
                'key1' => array('foo' => 'bar'),
                'key2' => new stdClass()
            )
        );
    }

    public function testSaveCachingDisabled()
    {
        $cache = Zend_Cache::factory('EC_Cache_Frontend_CoreMulti', 'EC_Cache_Backend_MockMulti', array(), array(), true, true);

        $cache->setOption('caching', false);

        $this->assertFalse($cache->saveMulti(
            array(
                'key1' => array('foo' => 'bar'),
                'key2' => new stdClass()
            )
        ));
    }

    public function testCachingDisabled()
    {
        $cache = Zend_Cache::factory('EC_Cache_Frontend_CoreMulti', 'EC_Cache_Backend_MockMulti', array(), array(), true, true);

        $cache->setOption('caching', false);

        $this->assertFalse(
            $cache->saveMulti(
                array(
                    'key1' => array('foo' => 'bar'),
                    'key2' => new stdClass()
                )
            )
        );

        $this->assertEmpty($cache->loadMulti(array('key1', 'key2')));
    }
}
