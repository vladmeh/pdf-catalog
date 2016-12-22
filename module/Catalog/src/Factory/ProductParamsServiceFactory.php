<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Factory;


use Catalog\Model\ProductParamsTable;
use Catalog\Service\ProductParamsService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ProductParamsServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ProductParamsService(
            $container->get(ProductParamsTable::class)
        );
    }
}