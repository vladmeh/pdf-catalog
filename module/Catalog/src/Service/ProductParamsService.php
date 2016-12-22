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
use Zend\Db\ResultSet\ResultSet;

class ProductParamsService implements ProductParamsServiceInterface
{
    /**
     * @var ProductParamsTable
     */
    private $_productParamsTable;

    public function __construct(ProductParamsTable $productParamsTable)
    {
        $this->_productParamsTable = $productParamsTable;
    }

    /**
     * @param bool $toArray
     * @return ProductParamsTable[]|ResultSet
     */
    public function fetchAll($toArray = false)
    {
        $result = $this->_productParamsTable->fetchAll();

        if ($toArray) {
            $resultArray = [];

            foreach ($result as $item)
                $resultArray[] = $item;

            return $resultArray;
        }

        return $result;
    }

}