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

class CategoriesService implements CategoriesServiceInterface
{

    private $_categoriesTable;

    public function __construct(CategoriesTable $categoriesTable)
    {
        $this->_categoriesTable = $categoriesTable;
    }

    /**
     * @param bool $toArray
     * @return array | Categories[]
     */
    public function fetchAll($toArray = false)
    {
        $result = $this->_categoriesTable->fetchAll();

        if ($toArray){
            $resultArray = [];

            foreach ($result as $item)
                $resultArray[] = $item;

            return $resultArray;
        }

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
     * @return array | Categories[]
     */
    public function fetchSubCategories($id, $toArray = false)
    {
        $result = $this->_categoriesTable->fetchSubCategories($id);

        if ($toArray){
            $resultArray = [];

            foreach ($result as $item)
                $resultArray[] = $item;

            return $resultArray;
        }

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
            $result[] = $item;
        }

        return $result;
    }
}