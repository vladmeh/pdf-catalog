<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;


use Catalog\Model\ProductParamsTable;
use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Db\ResultSet\ResultSet;

class ProductParamsService implements ProductParamsServiceInterface
{
    /**
     * @var ProductParamsTable
     */
    private $_productParamsTable;

    /**
     * @var Filesystem
     */
    private $_cache;


    public function __construct(
        ProductParamsTable $productParamsTable,
        Filesystem $filesystem
    )
    {
        $this->_productParamsTable = $productParamsTable;
        $this->_cache = $filesystem;
    }

    /**
     * @param bool $toArray
     * @return array|ResultSet
     */
    public function fetchAll($toArray = false)
    {
        $result = $this->_productParamsTable->fetchAll();

        if ($toArray) {

            $keyCache = 'productParams';
            $resultArray = $this->_cache->getItem($keyCache, $success);

            if(!$success){
                $resultArray = [];
                foreach ($result as $item)
                    $resultArray[$item->productId][$item->name] = $item->value;

                $this->_cache->setItem($keyCache, $resultArray);
            }

            return $resultArray;
        }

        return $result;
    }

}