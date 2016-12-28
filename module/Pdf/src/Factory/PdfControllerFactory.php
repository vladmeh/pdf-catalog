<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Pdf\Factory;


use Interop\Container\ContainerInterface;
use Pdf\Controller\PdfController;
use Pdf\Service\PdfServiceInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PdfControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PdfController(
            $container->get(PdfServiceInterface::class)
        );
    }
}