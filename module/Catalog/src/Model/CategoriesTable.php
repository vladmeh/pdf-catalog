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
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;

class CategoriesTable
{
    /**
     * @var TableGatewayInterface
     */
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($toArray = false)
    {
        $result = $this->tableGateway->select(function (Select $select){
                $select->where(['active' => 1, 'deleted' => 0]);
                $select->order('sorting ASC');
            });

        if ($toArray){
            $resultArray = [];

            foreach ($result as $item)
                $resultArray[] = $item;

            return $resultArray;
        }

        return $result;
    }

    public function getCategory($id)
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

    public function fetchSubCategories($id, $toArray = false)
    {
        $id = (int) $id;

        $result = $this->tableGateway->select(function (Select $select) use ($id){
            $select->where([
                'active' => 1,
                'deleted' => 0,
                'parent_id' => $id
            ]);
            $select->order('sorting ASC');
        });

        if ($toArray){
            $resultArray = [];

            foreach ($result as $item)
                $resultArray[] = $item;

            return $resultArray;
        }

        return $result;
    }

}