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
use Catalog\Service\ProductsServiceInterface;
use Catalog\Service\XmlServiceInterface;
use Zend\Debug\Debug;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class CatalogController extends AbstractActionController
{
    /**
     * @var CategoriesServiceInterface
     */
    private $categoriesService;

    /**
     * @var ProductsServiceInterface
     */
    private $productsService;

    /**
     * @var XmlServiceInterface
     */
    private $xmlService;

    public function __construct(
        CategoriesServiceInterface $categoriesService,
        ProductsServiceInterface $productsService,
        XmlServiceInterface $xmlService
    )
    {
        $this->categoriesService = $categoriesService;
        $this->productsService = $productsService;
        $this->xmlService = $xmlService;
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

    public function xmlAction()
    {
        $id = $this->params()->fromRoute('id');

        $response = new Response();
        $response->getHeaders()->addHeaderLine('Content-Type', 'text/xml; charset=utf-8');

        $xml = $this->xmlService->getXml();
        $xml->addChild('catalog', 'Alpha-Hydro');

        $tocXml = $xml->addChild('table_of_content');
        $this->xmlService->setSubCategoriesTree($id, $tocXml);

        $file_dir = __DIR__.'/../../../../data/xml';
        if(!file_exists($file_dir))
            mkdir($file_dir, 0755, true);

        $file_name = $file_dir.'/test.xml';

        $response->setContent($this->xmlService->output($xml, $file_name));
        return $response;
    }

    public function testAction()
    {
        $id = $this->params()->fromRoute('id');
        $result = $this->categoriesService->fetchCategoryProducts($id);

        //Debug::dump($result);die();
        return new JsonModel($result);
    }

}