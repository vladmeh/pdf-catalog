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

interface CategoriesServiceInterface
{
    /**
     * @return array | Categories[]
     */
    public function fetchAll();

    /**
     * @param $id
     * @return Categories
     */
    public function getCategory($id);

    /**
     * @param $id
     * @return array | Categories[]
     */
    public function fetchSubCategories($id);

    /**
     * @param $id
     * @return array | Categories[]
     */
    public function fetchTreeCategories($id);
}