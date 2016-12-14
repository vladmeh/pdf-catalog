<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;


use Catalog\Model\CategoriesTable;

class CategoriesService implements CategoriesServiceInterface
{
    /**
     * @var CategoriesTable
     */
    private $table;

    public function __construct(CategoriesTable $table)
    {
        $this->table = $table;
    }

    public function fetchAll($toArray = false)
    {

    }
}