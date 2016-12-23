<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


use RuntimeException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;

class ProductsTable
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
        return $this->tableGateway->select(function (Select $select){
            $select->join('categories_xref', 'products.id = categories_xref.product_id');
            $select->where(['active' => 1, 'deleted' => 0]);
            $select->order('sorting ASC');
        });
    }

    /**
     * @param $id
     * @return Products
     */
    public function getProduct($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    /**
     * @param $category_id
     * @return ResultSet
     */
    public function fetchProductsByCategory($category_id)
    {
        $category_id = (int) $category_id;

        return $this->tableGateway->select(function (Select $select) use ($category_id){
            $select
                ->join('categories_xref', 'products.id = categories_xref.product_id')
                ->where([
                    'category_id' => $category_id,
                    'active' => 1,
                    'deleted' => 0
                ])
                ->order('sorting ASC');
        });

    }

    public function fetchAllModificationParamValues()
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