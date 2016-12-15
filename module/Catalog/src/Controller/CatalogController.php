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
use Catalog\Service\CategoriesServiceInterface;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class CatalogController extends AbstractActionController
{
    /**
     * @var CategoriesTable
     */
    private $categoriesService;

    public function __construct(CategoriesServiceInterface $categoriesService)
    {
        $this->categoriesService = $categoriesService;
    }

    public function indexAction()
    {
        //Debug::dump($this->categoriesService->fetchAll(true));die();

        return new JsonModel($this->categoriesService->fetchAll(true));
    }

    public function viewAction(){
        $id = $this->params()->fromRoute('id');

        try {
            $category = $this->categoriesService->getCategory($id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('catalog');
        }

        return new JsonModel((array) $category);
    }

    public function categoriesAction()
    {
        $id = $this->params()->fromRoute('id');

        try {
            $category = $this->categoriesService->fetchSubCategories($id, true);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('catalog');
        }

        return new JsonModel($category);
    }

    public function treeAction()
    {
        $id = $this->params()->fromRoute('id');

        try {
            $result = $this->categoriesService->fetchTreeCategories($id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('catalog');
        }

        return new JsonModel($result);
    }

    public function testAction()
    {
        $id = $this->params()->fromQuery('categories');

        try {
            $result = $this->categoriesService->fetchTreeCategories($id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('catalog');
        }

        return new JsonModel($result);
    }

}