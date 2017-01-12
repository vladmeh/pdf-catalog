<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Controller;


use Catalog\Service\CategoriesServiceInterface;
use Catalog\Service\ModificationsServiceInterface;
use Catalog\Service\ProductParamsServiceInterface;
use Catalog\Service\ProductsServiceInterface;
use Catalog\Service\XmlServiceInterface;
use Zend\Debug\Debug;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
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
     * @var ProductParamsServiceInterface
     */
    private $productParamsService;

     /**
     * @var ModificationsServiceInterface
     */
    private $modificationService;

    /**
     * @var XmlServiceInterface
     */
    private $xmlService;

    public function __construct(
        CategoriesServiceInterface $categoriesService,
        ProductsServiceInterface $productsService,
        ProductParamsServiceInterface $productParamsService,
        ModificationsServiceInterface $modificationsService,
        XmlServiceInterface $xmlService
    )
    {
        $this->categoriesService = $categoriesService;
        $this->productsService = $productsService;
        $this->productParamsService = $productParamsService;
        $this->modificationService = $modificationsService;
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

        $productParams = $this->productParamsService->fetchAll(true);
        $this->xmlService->setProductParams($productParams);

        $modificationTable = $this->modificationService->fetchAll(true);
        $this->xmlService->setModificationsTable($modificationTable);

        $xml = $this->xmlService->getXml();
        $xml->addChild('catalog', 'Alpha-Hydro');

        //$tocXml = $xml->addChild('table_of_content');
        $this->xmlService->getXmlSubCategoriesTree($id, $xml);

        $file_dir = __DIR__.'/../../../../data/xml';
        if(!file_exists($file_dir))
            mkdir($file_dir, 0755, true);

        $file_name = $file_dir.'/catalog'.$id.'.xml';

        /*$response = new Response();
        $response->getHeaders()->addHeaderLine('Content-Type', 'text/xml; charset=utf-8');
        $response->setContent($this->xmlService->output($xml, $file_name));

        return $response;*/

        $this->xmlService->output($xml, $file_name);

        return new JsonModel([
            '_link' => $file_name
        ]);
    }

    public function testAction()
    {
        $id = $this->params()->fromRoute('id');
        $result = simplexml_load_file(__DIR__.'/../../../../data/xml/test.xml');

        echo $result->catalog.'<br/>';

        $xmlElement = $result->category;
        echo $this->_getXmlCategory($xmlElement);

        //Debug::dump($result);die();
        //return new JsonModel($result);
    }

    /**
     * @param array $xmlElements
     * @return string
     */
    private function _getXmlCategory($xmlElements)
    {
        $html = '<ul>';

        foreach ($xmlElements as $item) {
            $html .= '<li>'.$item->attributes()->name;
            if($item->category)
                $html .= $this->_getXmlCategory($item->category);
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

}