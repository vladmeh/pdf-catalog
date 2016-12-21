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
use Zend\Db\ResultSet\ResultSet;

interface ProductsServiceInterface
{
    /**
     * @param bool $toArray
     * @return Products[]|ResultSet
     */
    public function fetchAll($toArray = false);

    /**
     * @param $id
     * @return Products
     */
    public function getProduct($id);

    /**
     * @param $category_id
     * @param bool $toArray
     * @return ResultSet | Products[]
     */
    public function fetchProductsByCategory($category_id, $toArray = false);
}