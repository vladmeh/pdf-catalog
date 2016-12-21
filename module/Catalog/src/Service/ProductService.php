<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;


use Catalog\Model\Products;
use Catalog\Model\ProductsTable;
use Zend\Db\ResultSet\ResultSet;

class ProductService implements ProductsServiceInterface
{
    /**
     * @var ProductsTable
     */
    private $_productsTable;

    public function __construct(ProductsTable $productsTable)
    {
        $this->_productsTable = $productsTable;
    }

    /**
     * @param bool $toArray
     * @return array|\Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($toArray = false)
    {
        $result = $this->_productsTable->fetchAll();

        if($toArray){
            $resultArray = [];

            foreach ($result as $item)
                $resultArray[] = $item;

            return $resultArray;
        }

        return $result;
    }

    /**
     * @param $id
     * @return Products
     */
    public function getProduct($id)
    {
        return $this->_productsTable->getProduct($id);
    }

    /**
     * @param $category_id
     * @param bool $toArray
     * @return ResultSet | Products[]
     */
    public function fetchProductsByCategory($category_id, $toArray = false)
    {
        $result = $this->_productsTable->fetchProductsByCategory($category_id);

        if($toArray){
            $resultArray = [];

            foreach ($result as $item)
                $resultArray[] = $item;

            return $resultArray;
        }

        return $result;
    }
}