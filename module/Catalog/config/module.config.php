<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Router\Http\Segment;

return [
    'service_manager' => [
        'factories' => [
            Model\CategoriesTable::class => function(ContainerInterface $container) {
                $tableGateway = $container->get(Model\CategoriesTableGateway::class);
                return new Model\CategoriesTable($tableGateway);
            },
            Model\CategoriesTableGateway::class => function (ContainerInterface $container) {
                $dbAdapter = $container->get(AdapterInterface::class);
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Model\Categories());
                return new TableGateway('categories', $dbAdapter, null, $resultSetPrototype);
            },
            Model\ProductsTable::class => function(ContainerInterface $container) {
                $tableGateway = $container->get(Model\ProductsTableGateway::class);
                return new Model\ProductsTable($tableGateway);
            },
            Model\ProductsTableGateway::class => function (ContainerInterface $container) {
                $dbAdapter = $container->get(AdapterInterface::class);
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Model\Products());
                return new TableGateway('products', $dbAdapter, null, $resultSetPrototype);
            },
            Service\CategoriesServiceInterface::class => Factory\CategoriesServiceFactory::class,
            Service\ProductsServiceInterface::class => Factory\ProductsServiceFactory::class,
            Service\XmlServiceInterface::class => Factory\XmlServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\CatalogController::class => Factory\CatalogControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'catalog' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/catalog[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CatalogController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'catalog' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy'
        ]
    ],
];