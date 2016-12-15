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
     * @param \SimpleXMLElement $xml
     * @param  string | null $toFile
     * @return mixed
     */
    public function output($xml, $toFile = null)
    {
        $xml->addChild('catalog', 'pdf');

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
     */
    public function setXml($xml)
    {
        $this->_xml = $xml;
    }

}