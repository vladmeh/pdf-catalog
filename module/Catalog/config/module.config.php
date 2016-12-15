<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog;

use Zend\Router\Http\Segment;

return [
    'service_manager' => [
        'factories' => [
            Model\CategoriesTable::class => Factory\CategoriesTableFactory::class,
            Model\CategoriesTableGateway::class => Model\CategoriesTableGateway::class,
            Service\CategoriesServiceInterface::class => Factory\CategoriesServiceFactory::class,
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