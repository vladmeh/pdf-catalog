<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;


use Catalog\Model\ModificationsTable;
use Zend\Cache\Storage\Adapter\Filesystem;

class ModificationService implements ModificationsServiceInterface
{
    /**
     * @var ModificationsTable
     */
    private $_modificationsTable;

    /**
     * @var Filesystem
     */
    private $_cache;

    public function __construct(
        ModificationsTable $modificationsTable,
        Filesystem $filesystem
    )
    {
        $this->_modificationsTable = $modificationsTable;
        $this->_cache = $filesystem;
    }

    public function fetchAll($toArray = false)
    {
        $result = $this->_modificationsTable->fetchAll();

        if($toArray){
            $keyCache = 'modificationsTable';
            $resultCache = $this->_cache->getItem($keyCache, $success);

            if(!$success){
                $resultArray = [];
                foreach ($result as $item)
                    $resultArray[] = (array) $item;

                $resultCache = [];

                $productParams = $this->arrayGroupBy($resultArray, ['productId', 'paramName']);
                foreach ($productParams as $id => $product)
                    foreach ($product as $name => $param)
                        $resultCache[$id]['columns'][] = $name;

                $productsModification = $this->arrayGroupBy($resultArray, ['productId', 'modificationName']);
                foreach ($productsModification as $id => $modifications)
                    foreach ($modifications as $n => $params)
                        foreach ($params as $param)
                            $resultCache[$id]['rows'][$n][] = $param['paramValue'];

                $this->_cache->setItem($keyCache, $resultCache);
            }

            return $resultCache;
        }

        return $result;
    }

    private function arrayGroupBy(&$array, $keys)
    {
        $result = [];

        $k = 0;
        $_key = $keys[$k];
        foreach ($array as $value){
            $key = $value[ $_key ];
            unset($value[$_key]);
            $result[$key][] = $value;
        }

        if(count($keys) > 1){
            array_shift($keys);
            foreach ($result as $key => $value){
                $result[$key] = $this->arrayGroupBy($value, $keys);
            }
        }
        return $result;
    }

}