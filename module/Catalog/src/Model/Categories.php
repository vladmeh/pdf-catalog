<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


class Categories
{
    public $id;
    public $parentId;
    public $name;
    public $fullPath;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->parentId = !empty(['parent_id']) ? $data['parent_id'] : null;
        $this->name = !empty(['name']) ? $data['name'] : null;
        $this->fullPath = !empty(['full_path']) ? $data['full_path'] : null;
    }
}