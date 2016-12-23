<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;


interface XmlServiceInterface
{
    /**
     * @param $category_id
     * @param null|\SimpleXMLElement $xmlElement
     * @return null|\SimpleXMLElement
     */
    public function getXmlSubCategoriesById($category_id, $xmlElement = null);

    /**
     * @param $category_id
     * @param null|\SimpleXMLElement $xmlElement
     * @return null|\SimpleXMLElement
     */
    public function getXmlSubCategoriesTree($category_id, $xmlElement = null);

    /**
     * @param $category_id
     * @param \SimpleXMLElement $xmlElement
     * @return \SimpleXMLElement
     */
    public function getXmlCategoryProducts($category_id, $xmlElement);

    /**
     * @param $xml \SimpleXMLElement
     * @param string | null $toFile
     * @return mixed
     */
    public function output($xml = null, $toFile = null);

    /**
     * @return \SimpleXMLElement
     */
    public function getXml();

    /**
     * @param array $productParams
     * @return array
     */
    public function setProductParams($productParams);

    /**
     * @param array $modificationsTable
     * @return array
     */
    public function setModificationsTable($modificationsTable);
}