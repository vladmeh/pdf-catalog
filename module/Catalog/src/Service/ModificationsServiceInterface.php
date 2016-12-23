<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;


use Catalog\Model\Modifications;
use Zend\Db\ResultSet\ResultSet;

interface ModificationsServiceInterface
{
    /**
     * @param bool $toArray
     * @return Modifications[]|ResultSet
     */
    public function fetchAll($toArray = false);
}