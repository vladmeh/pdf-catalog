<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Pdf\Controller;

use Dompdf\Dompdf;
use Zend\Mvc\Controller\AbstractActionController;

class PdfController extends AbstractActionController
{
    public function indexAction()
    {
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml('<p>Hello World</p>');

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A5', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream(null, ["Attachment"=>0]);
    }
}