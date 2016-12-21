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
    public function setSubCategoriesById($category_id, $xmlElement = null);

    /**
     * @param $category_id
     * @param null|\SimpleXMLElement $xmlElement
     * @return null|\SimpleXMLElement
     */
    public function setSubCategoriesTree($category_id, $xmlElement = null);

    /**
     * @param $category_id
     * @param \SimpleXMLElement $xmlElement
     * @return \SimpleXMLElement
     */
    public function setCategoryProducts($category_id, $xmlElement);

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
}