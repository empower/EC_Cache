<?php

require_once 'Zend/Cache/Core.php';
require_once 'EC/Cache/MultiInterface.php';

/**
 * Extension of Zend_Cache_Core to add loadMulti() and saveMulti() functionality.
 * Intented to work with EC_Cache_Backend_LoadmemcachedMulti.
 *
 * @uses      Zend_Cache_Core
 * @category  Zend
 * @package   EC_Cache
 * @copyright 2011 Bill Shupp
 * @author    Bill Shupp <hostmaster@shupp.org>
 * @license   http://www.opensource.org/licenses/bsd-license.php FreeBSD
 * @link      http://github.com/empower/EC_Cache
 */
class EC_Cache_Frontend_CoreMulti extends Zend_Cache_Core implements EC_Cache_MultiInterface
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
    public function loadMulti(array $ids, $doNotUnserialize = false)
    {
        if (!$this->_options['caching']) {
            return array();
        }

        // Prefix IDs
        $prefixMap = array();
        foreach($ids as $id) {
            $newId             = $this->_id($id);
            $prefixMap[$newId] = $id; // cache id may need prefix
            self::_validateIdOrTag($prefixMap[$newId]);
        }
        $this->_lastId = $ids;

        $this->_log(
            "Zend_Cache_Core: load items '" . print_r($prefixMap, true) . "'", 7
        );
        $data = $this->_backend->loadMulti(array_keys($prefixMap));

        $returnValues = array();
        foreach ($data as $key => $value) {
            if ((!$doNotUnserialize) && $this->_options['automatic_serialization']) {
                // we need to unserialize before sending the result
                $value = unserialize($value);
            }

            $returnValues[$prefixMap[$key]] = $value;
        }

        return $returnValues;
    }

    /**
     * Adds multi set support to the interface, needed for memcached
     * via EC_Cache_Backend_LoadmemcachedMulti
     *
     * @param array $data             The key/values to set at one time
     * @param mixed $specificLifetime Optional lifetime for this store
     *
     * @return bool
     */
    public function saveMulti(array $data, $specificLifetime = false)
    {
        if (!$this->_options['caching']) {
            return false;
        }

        // Update
        $newData = array();
        foreach ($data as $key => $value) {
            $newId = $this->_id($key);
            self::_validateIdOrTag($newId);
            if ($this->_options['automatic_serialization']) {
                // we need to serialize datas before storing them
                $value = serialize($value);
            } else {
                if (!is_string($value)) {
                    Zend_Cache::throwException(
                        "Datas must be string or set automatic_serialization = true"
                    );
                // @codeCoverageIgnoreStart
                // Bogus line coverage in PHPUnit
                }
                // @codeCoverageIgnoreEnd
            }
            $newData[$newId] = $value;
        }

        $this->_log(
            "Zend_Cache_Core: save item '" . print_r(array_keys($data), true),
            7
        );
        if ($this->_options['ignore_user_abort']) {
            $abort = ignore_user_abort(true);
        }
        $result = $this->_backend->saveMulti($newData, $specificLifetime);
        if ($this->_options['ignore_user_abort']) {
            ignore_user_abort($abort);
        }

        return $result;
    }
}
