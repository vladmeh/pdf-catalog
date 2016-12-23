<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;

class ModificationsTable
{
    /**
     * @var TableGateway
     */
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return ResultSet
     */
    public function fetchAll()
    {
        return $this->tableGateway->select(function(Select $select) {
            $select
                ->columns(['productId' => 'id'])
                ->join('subproduct_params', 'products.id = subproduct_params.product_id', ['paramName' => 'name'])
                ->join('subproduct_params_values', 'subproduct_params.id = subproduct_params_values.param_id', ['paramValue' => 'value'])
                ->join('subproducts', 'subproduct_params_values.subproduct_id = subproducts.id', ['modificationName' => 'sku'])
                ->where([
                    'products.active' => 1,
                    'products.deleted' => 0
                ])
                ->order('subproduct_params.order ASC');
        });
    }
}