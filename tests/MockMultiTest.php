<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Cache.php';

class EC_Cache_Backend_MockMultiTest extends PHPUnit_Framework_TestCase
{
    protected $_cache = null;

    public function setUp()
    {
        $frontendOptions = array(
            'cache_id_prefix' => null,
            'automatic_serialization' => true,
        );

        $this->_cache = Zend_Cache::factory('EC_Cache_Frontend_CoreMulti', 'EC_Cache_Backend_MockMulti',
                $frontendOptions, array(), true, true);
    }

    public function testMultiSaveLoad()
    {
        $this->_cache->saveMulti(array('key' => 'value'));
        $this->assertSame(array('key' => 'value'), $this->_cache->loadMulti(array('key')));
        $this->_cache->remove('key');
        $this->assertEmpty($this->_cache->loadMulti(array('key')));
    }

    public function testSaveMultiReturnsFalse()
    {
        $cache = $this->getMock('EC_Cache_Backend_MockMulti', array('save'), array(), '', false);
        $cache->expects($this->once())
              ->method('save')
              ->will($this->returnValue(false));
        $this->assertFalse($cache->saveMulti(array('key' => 'value')));
    }
}
