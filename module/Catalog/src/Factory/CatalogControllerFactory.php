<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Factory;

use Catalog\Controller\CatalogController;
use Catalog\Service\CategoriesServiceInterface;

use Catalog\Service\ProductsServiceInterface;
use Catalog\Service\XmlServiceInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CatalogControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CatalogController(
            $container->get(CategoriesServiceInterface::class),
            $container->get(ProductsServiceInterface::class),
            $container->get(XmlServiceInterface::class)
        );
    }
}