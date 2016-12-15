<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Factory;


use Catalog\Model\CategoriesTable;
use Catalog\Service\XmlService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class XmlServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $simpleXMLElement = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root/>');

        return new XmlService(
            $container->get(CategoriesTable::class),
            $simpleXMLElement
        );
    }
}