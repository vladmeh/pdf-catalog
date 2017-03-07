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
use Zend\EventManager\EventManager;
use Zend\Http\Response;
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
        return new ViewModel();
    }


    public function createAction()
    {
        $id = $this->params()->fromRoute('id');

        $pdf = $this->pdfService;
        $pdf->defaultSettingsPage();

        //Введение
        $view = new ViewModel();
        $view->setTemplate('pdf/pdf/introduction');
        $html = $this->renderer->render($view);
        $pdf->introduction($html);

        $xmlFileDir = __DIR__.'/../../../../data/xml';
        $xmlFileName = 'catalog'.$id.'.xml';

        //Вынести в конструктор
        //Проверка на существование файла
        //Если файла нет перенаправление на создание файла
        $xmlObject = simplexml_load_file($xmlFileDir.'/'.$xmlFileName);
        $pdf->content($xmlObject);

        $file_dir = __DIR__.'/../../../../public/data';
        if(!file_exists($file_dir))
            mkdir($file_dir, 0755, true);

        $file_name = 'catalog'.$id.'.pdf';

        $pdf->Output($file_dir.'/'.$file_name, 'F');

        return new JsonModel(['_link' => 'data/'.$file_name]);
    }

    public function testAction()
    {
        $id = $this->params()->fromRoute('id');

        $xmlFileDir = __DIR__.'/../../../../data/xml';
        $xmlFileName = 'catalog'.$id.'.xml';

        $reader = new \XMLReader();
        $reader->open($xmlFileDir.'/'.$xmlFileName);
        //Debug::dump($reader->xmlLang);

        while($reader->read()) {
            if($reader->nodeType == \XMLReader::ELEMENT) {
                // если находим элемент <card>
                if($reader->localName == 'product') {

                    Debug::dump(simplexml_load_string($reader->readOuterXML()));

                }
            }
        }
        die();
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