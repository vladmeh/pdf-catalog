<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Pdf\Controller;


use Pdf\Service\PdfServiceInterface;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;

class PdfController extends AbstractActionController
{
    /**
     * @var PdfServiceInterface
     */
    private $pdfService;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    public function __construct(
        PdfServiceInterface $pdfService,
        RendererInterface $renderer
    )
    {
        $this->pdfService = $pdfService;
        $this->renderer = $renderer;
    }

    public function indexAction()
    {
        $pdf = $this->pdfService;
        $pdf->defaultSettingsPage();

        //Введение
        $view = new ViewModel();
        $view->setTemplate('pdf/pdf/introduction');
        $html = $this->renderer->render($view);
        $pdf->introduction($html);

        $pdf->Output('catalog.pdf', 'I');
    }

    public function testAction()
    {
        $id = $this->params()->fromRoute('id');
        $result = simplexml_load_file(__DIR__.'/../../../../data/xml/test.xml');

        echo $result->catalog.'<br/>';

        $xmlElement = $result->category;
        echo $this->_getXmlCategory($xmlElement);

        //Debug::dump($result);die();
        //return new JsonModel($result);
    }

    /**
     * @param array $xmlElements
     * @return string
     */
    private function _getXmlCategory($xmlElements)
    {
        $html = '<ul>';

        foreach ($xmlElements as $item) {
            $html .= '<li>'.$item->attributes()->name;
            if($item->category)
                $html .= $this->_getXmlCategory($item->category);
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }
}