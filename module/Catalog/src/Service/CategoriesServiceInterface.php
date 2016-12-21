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
use Catalog\Model\Products;
use Zend\Db\ResultSet\ResultSet;

interface CategoriesServiceInterface
{
    /**
     * @param bool $toArray
     * @return Categories[]|ResultSet
     */
    public function fetchAll($toArray = false);

    /**
     * @param $id
     * @return Categories
     */
    public function getCategory($id);

    /**
     * @param $id
     * @param bool $toArray
     * @return Categories[]|ResultSet
     */
    public function fetchSubCategories($id, $toArray = false);

    /**
     * @param $id
     * @return array | Categories[]
     */
    public function fetchTreeCategories($id);

    /**
     * @param $id
     * @param null $products
     * @return array|Products[]
     */
    public function fetchCategoryProducts($id, &$products = null);
}