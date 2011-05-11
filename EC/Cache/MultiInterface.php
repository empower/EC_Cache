<?php

/**
 * An interface defining support for loadMulti and saveMulti operations, used with
 * memcached getMulti and setMulti, respectively.
 *
 * @category  Zend
 * @package   EC_Cache
 * @copyright 2011 Empower Campaigns
 * @author    Bill Shupp <hostmaster@shupp.org>
 * @license   http://www.opensource.org/licenses/bsd-license.php FreeBSD
 * @link      http://github.com/empower/EC_Cache
 */
interface EC_Cache_MultiInterface
{
    /**
     * Adds multi get support to the interface, needed for memcached
     * via EC_Cache_Backend_LoadmemcachedMulti
     *
     * @param array $ids              Array of keys to load at once
     * @param bool  $doNotUnserialize Whethere or not to skip serializing
     *
     * @return array
     */
    public function loadMulti(array $ids, $doNotUnserialize = false);

    /**
     * Adds multi set support to the interface, needed for memcached
     * via EC_Cache_Backend_LoadmemcachedMulti
     *
     * @param array $data             The key/values to set at one time
     * @param mixed $specificLifetime Optional lifetime for this store
     *
     * @return bool
     */
    public function saveMulti(array $data, $specificLifetime = false);
}
