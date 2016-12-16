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

class XmlService implements XmlServiceInterface
{

    /**
     * @var CategoriesTable
     */
    private $_categoriesTable;

    private $_xml;

    public function __construct(
        CategoriesTable $categoriesTable,
        \SimpleXMLElement $simpleXMLElement
    )
    {
        $this->_categoriesTable = $categoriesTable;
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
            $category = $xmlElement->addChild('category',$item->name);
            $category->addAttribute('id', $item->id);
            //$category->addAttribute('name', $item->name);
            $category->addAttribute('level', $level);
            $subCategory = $this->_categoriesTable->fetchSubCategories($item->id);
            if(0 != $subCategory->count() && $level < 3)
                $this->setSubCategoriesTree($item->id, $category, $level+1);
        }

        return $xmlElement;
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