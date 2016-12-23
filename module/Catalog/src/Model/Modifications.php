<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


class Modifications
{
    public $productId;
    public $modificationName;
    public $paramName;
    public $paramValue;

    public function exchangeArray(array $data)
    {
        $this->productId = !empty($data['productId']) ? $data['productId'] : null;
        $this->modificationName = !empty($data['modificationName']) ? $data['modificationName'] : null;
        $this->paramName = !empty($data['paramName']) ? $data['paramName'] : null;
        $this->paramValue = !empty($data['paramValue']) ? $data['paramValue'] : null;
    }

}