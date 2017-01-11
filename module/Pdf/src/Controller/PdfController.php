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
    protected $pdfService;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    private $_progress;

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

        //Вынести в конструктор
        //Проверка на существование файла
        //Если файла нет перенаправление на создание файла
        $xmlObject = simplexml_load_file(__DIR__.'/../../../../data/xml/test.xml');
        $pdf->content($xmlObject);

        $pdf->Output('/catalog.pdf', 'F');
    }

    public function testAction()
    {
       /* $i = 1;
        while ($i <= 10) {
            sleep(3);
            $this->setProgress($i);
        }*/

        return new ViewModel();
    }


    public function liveTimeAction()
    {
        $time = date("H:i:s", time());

        $data = new JsonModel(array(
            'time' => $time,
        ));
        return $data;
    }

    /**
     * @return mixed
     */
    public function getProgress()
    {
        return $this->_progress;
    }

    /**
     * @param mixed $progress
     */
    public function setProgress($progress)
    {
        $this->_progress = $progress;
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