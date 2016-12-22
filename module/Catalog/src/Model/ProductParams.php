<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


class ProductParams
{
    public $productId;
    public $name;
    public $value;

    public function exchangeArray(array $data)
    {
        $this->productId = !empty($data['product_id']) ? $data['product_id'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->value = !empty($data['value']) ? $data['value'] : null;
    }
}