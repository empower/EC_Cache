<?php

require_once 'Zend/Cache/Backend/Libmemcached.php';

/**
 * Temporary implementation of multiGet and multiSet for memcached.  ZF2 should
 * have proper support, we'll use that when it's available.
 *
 * @uses      Zend_Cache_Backend_Libmemcached
 * @category  Zend
 * @package   Enterprise
 * @author    Bill Shupp <hostmaster@shupp.org>
 * @copyright 2010-2011 Empower Campaigns
 * @link      http://github.com/empower/EC_Cache
 * @license   http://www.opensource.org/licenses/bsd-license.php FreeBSD
 */
class EC_Cache_Backend_LibmemcachedMulti extends Zend_Cache_Backend_Libmemcached
{
    /**
     * Interface for loading multiple keys at once.  Misses are empty, not false.
     *
     * @param array $ids Array of keys to get
     *
     * @return array
     */
    public function loadMulti(array $ids)
    {
        $fromCache = $this->_getMemcached()->getMulti($ids);
        $results   = array();
        if ($fromCache === false) {
            return $results;
        }
        foreach ($fromCache as $key => $value) {
            if (isset($value[0])) {
                $results[$key] = $value[0];
            }
        }
        return $results;
    }

    /**
     * Saves multiple keys in memcache at once
     *
     * @param array $items            Key/Value pairs of items to save
     * @param mixed $specificLifetime Optional lifetime for these items
     *
     * @return bool
     */
    public function saveMulti(array $items, $specificLifetime = false)
    {
        $lifetime  = $this->getLifetime($specificLifetime);
        $memcached = $this->_getMemcached();

        $newItems = array();
        foreach ($items as $key => $value) {
            $newItems[$key] = array($value, time(), $lifetime);
        }

        $result = $memcached->setMulti($newItems, $lifetime);
        if ($result === false) {
            $rsCode = $memcached->getResultCode();
            $rsMsg  = $memcached->getResultMessage();
            $this->_log("Memcached::set() failed: [{$rsCode}] {$rsMsg}");
        }

        return $result;
    }

    // @codeCoverageIgnoreStart
    protected function _getMemcached()
    {
        return $this->_memcache;
    }
    // @codeCoverageIgnoreEnd
}
