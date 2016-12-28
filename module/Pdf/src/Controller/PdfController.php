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

class PdfController extends AbstractActionController
{
    /**
     * @var PdfServiceInterface
     */
    private $pdfService;

    public function __construct(PdfServiceInterface $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function indexAction()
    {
        $pdf = $this->pdfService;
        $pdf->defaultSettingsPage();

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