<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;


use Catalog\Model\Categories;
use Catalog\Model\CategoriesTable;
use Catalog\Model\ProductsTable;
use Zend\Db\ResultSet\ResultSet;

class CategoriesService implements CategoriesServiceInterface
{

    /**
     * @var CategoriesTable
     */
    private $_categoriesTable;

    /**
     * @var ProductsTable
     */
    private $_productsTable;

    public function __construct(
        CategoriesTable $categoriesTable,
        ProductsTable $productsTable
    )
    {
        $this->_categoriesTable = $categoriesTable;
        $this->_productsTable = $productsTable;
    }

    /**
     * @param bool $toArray
     * @return ResultSet | Categories[]
     */
    public function fetchAll($toArray = false)
    {
        $result = $this->_categoriesTable->fetchAll();

        if ($toArray)
            return $this->_toArray($result);

        return $result;
    }

    /**
     * @param $id
     * @return Categories
     */
    public function getCategory($id)
    {
        return $this->_categoriesTable->getCategory($id);
    }

    /**
     * @param $id
     * @param bool $toArray
     * @return ResultSet | Categories[]
     */
    public function fetchSubCategories($id, $toArray = false)
    {
        $result = $this->_categoriesTable->fetchSubCategories($id);

        if ($toArray)
            return $this->_toArray($result);

        return $result;
    }

    /**
     * @param $id
     * @param int $level
     * @return array | Categories[]
     */
    public function fetchTreeCategories($id, $level = 0)
    {
        $result = array();
        $resultSet = $this->fetchSubCategories($id, true);

        foreach ($resultSet as $item) {
            $item->level = $level;
            $subCategories = $this->fetchSubCategories($item->id, true);
            if(0 != count($subCategories) && $level < 2){
                $item->subCategories = $this->fetchTreeCategories($item->id, $level+1);
            }
            else{
                $item->products = $this->fetchCategoryProducts($item->id);
            }
            $result[] = $item;
        }

        return $result;
    }


    public function fetchCategoryProducts($id, &$result = null)
    {
        if(is_null($result))
            $result = [];

        $productCategory = $this->_productsTable->fetchProductsByCategory($id);
        if(0 != $productCategory->count())
            $result = array_merge($result, $this->_toArray($productCategory));

        $subCategories = $this->_categoriesTable->fetchSubCategories($id);
        if(0 != $subCategories->count()){
            foreach ($subCategories as $subCategory){
                $productSubCategory = $this->_productsTable->fetchProductsByCategory($subCategory->id);
                $result = array_merge($result, $this->_toArray($productSubCategory));
                $children = $this->_categoriesTable->fetchSubCategories($subCategory->id);
                if(0 != $children->count())
                    $this->fetchCategoryProducts($subCategory->id, $result);
            }
        }

        return $result;
    }

    private function _toArray(ResultSet $resultSet)
    {
        $result = [];

        foreach ($resultSet as $item)
            $result[] = $item;

        return $result;
    }
}