<?php

require_once 'EC/Cache/Backend/Mock.php';

/**
 * Mock implementations of the EC_Cache_MultiInterface for testing purposes
 *
 * @uses      EC_Cache_Backend_Mock
 * @category  Zend
 * @package   EC_Cache
 * @copyright 2011 Empower Campaigns
 * @author    Bill Shupp <hostmaster@shupp.org>
 * @license   http://www.opensource.org/licenses/bsd-license.php FreeBSD
 * @link      http://github.com/empower/EC_Cache
 */
class EC_Cache_Backend_MockMulti extends EC_Cache_Backend_Mock
{
    /**
     * Mock implementation of loadMulti
     *
     * @param array $ids Array of ids to load
     *
     * @return array
     */
    public function loadMulti(array $ids)
    {
        $returnValues = array();

        foreach ($ids as $id) {
            $result = $this->load($id);
            if ($result !== false) {
                $returnValues[$id] = $result;
            }
        }
        return $returnValues;
    }

    /**
     * Mock implementation of saveMulti()
     *
     * @param array $items            Array of key/values to save
     * @param mixed $specificLifetime Optional specific lifetime for these items
     *
     * @return bool
     */
    public function saveMulti(array $items, $specificLifetime = false)
    {
        $return = true;
        foreach ($items as $id => $value) {
            $response = $this->save($value, $id, array(), $specificLifetime);
            if ($response === false) {
                $return = $response;
            }
        }
        return $return;
    }
}
