<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Controller;


use Catalog\Model\CategoriesTable;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class CatalogController extends AbstractActionController
{
    /**
     * @var CategoriesTable
     */
    private $table;

    public function __construct(CategoriesTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        //Debug::dump($categories);die();

        return new JsonModel($this->table->fetchAll(true));
    }

    public function categoriesAction()
    {
        $id = $this->params()->fromRoute('id');

        return new JsonModel($this->table->fetchSubCategories($id, true));
    }

    public function testAction()
    {
        //Debug::dump($request->fromQuery());die();
        $id = $this->params()->fromQuery('categories');
        return new JsonModel($this->recursiveGroupTreeCategories($id));
    }

    public function recursiveGroupTreeCategories($id, $level = 0)
    {
        $result = array();
        $resultSet = $this->table->fetchSubCategories($id, true);

        foreach ($resultSet as $item) {
            $item->level = $level;
            $subCategories = $this->table->fetchSubCategories($item->id, true);
            if(0 != count($subCategories) && $level < 2){
                $item->subCategories = $this->recursiveGroupTreeCategories($item->id, $level+1);
            }
            $result[] = $item;
        }

        return $result;
    }

}