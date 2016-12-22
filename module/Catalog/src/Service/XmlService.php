<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;


use Catalog\Model\CategoriesTable;
use Catalog\Model\Products;
use Catalog\Model\ProductsTable;
use Zend\Db\ResultSet\ResultSet;

class XmlService implements XmlServiceInterface
{

    /**
     * @var CategoriesTable
     */
    private $_categoriesTable;

    /**
     * @var ProductsTable
     */
    private $_productsTable;

    private $_xml;

    public function __construct(
        CategoriesTable $categoriesTable,
        ProductsTable $productsTable,
        \SimpleXMLElement $simpleXMLElement
    )
    {
        $this->_categoriesTable = $categoriesTable;
        $this->_productsTable = $productsTable;
        $this->_xml = $simpleXMLElement;
    }


    /**
     * @param $category_id
     * @param null|\SimpleXMLElement $xmlElement
     * @return null|\SimpleXMLElement
     */
    public function setSubCategoriesById($category_id, $xmlElement = null)
    {
        $id = (int) $category_id;

        if(is_null($xmlElement))
            $xmlElement = $this->getXml();

        $resultSet = $this->_categoriesTable->fetchSubCategories($id);
        foreach ($resultSet as $item) {
            $category = $xmlElement->addChild('category');
            $category->addAttribute('id', $item->id);
            $category->addAttribute('name', $item->name);
        }

        return $xmlElement;
    }

    public function setSubCategoriesTree($category_id, $xmlElement = null, $level = 1)
    {
        $id = (int) $category_id;

        if(is_null($xmlElement))
            $xmlElement = $this->getXml();

        $resultSet = $this->_categoriesTable->fetchSubCategories($id);
        foreach ($resultSet as $item) {
            $categoryXml = $xmlElement->addChild('category');
            $categoryXml->addAttribute('id', $item->id);
            $categoryXml->addAttribute('name', $item->name);
            $categoryXml->addAttribute('level', $level);
            $subCategory = $this->_categoriesTable->fetchSubCategories($item->id);
            if(0 != $subCategory->count() && $level < 3){
                $this->setSubCategoriesTree($item->id, $categoryXml, $level+1);
            }
            else{
                $categoryXml->addAttribute('path', 'http://alpha-hydro.com/catalog/'.$item->fullPath);
                //$products = $category->addChild('products');
                $this->setCategoryProducts($item->id, $categoryXml);
            }
        }

        return $xmlElement;
    }

    /**
     * @param $category_id
     * @param \SimpleXMLElement $xmlElement
     * @return \SimpleXMLElement
     */
    public function setCategoryProducts($category_id, $xmlElement)
    {
        $productCategory = $this->_productsTable->fetchProductsByCategory($category_id);
        if(0 != $productCategory->count())
            $this->setProducts($productCategory, $xmlElement);

        $subCategories = $this->_categoriesTable->fetchSubCategories($category_id);
        if(0 != $subCategories->count()){
            foreach ($subCategories as $subCategory){
                $productSubCategory = $this->_productsTable->fetchProductsByCategory($subCategory->id);
                if(0 != $productSubCategory)
                    $this->setProducts($productSubCategory, $xmlElement);

                $children = $this->_categoriesTable->fetchSubCategories($subCategory->id);
                if(0 != $children->count())
                    $this->setCategoryProducts($subCategory->id, $xmlElement);
            }
        }

        return $xmlElement;
    }

    /**
     * @param arrayObject Products[] | ResultSet
     * @param \SimpleXMLElement $element
     * @return $this
     */
    public function setProducts($resultSet, \SimpleXMLElement $element)
    {
        foreach ($resultSet as $arrayObject){
            $productXml = $element->addChild('product');
            $this->setProduct($arrayObject, $productXml);
        }

        return $this;
    }

    /**
     * @param Products $productObject
     * @param \SimpleXMLElement $productXml
     * @return $this
     */
    public function setProduct(Products $productObject, \SimpleXMLElement $productXml)
    {
        $productXml->addAttribute('id', $productObject->id);
        $productXml->addChild('sku', $productObject->sku);
        $productXml->addChild('name', $productObject->name);
        $productXml->addChild('image', $productObject->image)->addAttribute('path', $productObject->uploadPath);

        if($productObject->draft)
            $productXml->addChild('draft', $productObject->draft)->addAttribute('path', $productObject->uploadPathDraft);

        return $this;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param  string | null $toFile
     * @return mixed
     */
    public function output($xml = null, $toFile = null)
    {
        if(is_null($xml))
            $xml = $this->getXml();

        if($toFile)
            $xml->asXML($toFile);

        return $xml->asXML();
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getXml()
    {
        return $this->_xml;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return $this
     */
    public function setXml($xml)
    {
        $this->_xml = $xml;
        return $this;
    }

}