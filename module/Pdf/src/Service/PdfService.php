<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Pdf\Service;


use Zend\Debug\Debug;

class PdfService extends \TCPDF implements PdfServiceInterface
{
    /**
     * @var int
     */
    protected $_widthWorkspacePage;

    /**
     * @var string
     */
    protected $_image_field_bookmark;

    public function __construct()
    {
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->setWidthWorkspacePage($this->getPageWidth()-(PDF_MARGIN_LEFT+PDF_MARGIN_RIGHT));
        $this->setImageFieldBookmark('fieldBookmark.png');
    }

    public function Header()
    {
        $headerfont = $this->getHeaderFont();
        $headerdata = $this->getHeaderData();

        $this->SetFont($headerfont[0], 'B', $headerfont[2] + 1);

        $align = ($this->getNumPages() % 2 === 0)?'':'R';

        $this->Cell(0,0,$headerdata['title'], 0, 0, $align);

        $this->SetY(15);
        // set colors for gradients (r,g,b) or (grey 0-255)
        $blue = array(0, 141, 210);
        $white = array(255, 255, 255);

        // set the coordinates x1,y1,x2,y2 of the gradient (see linear_gradient_coords.jpg)
        $coords = array(0, 0, 1, 0);
        $wl = $this->w-$this->lMargin-$this->rMargin;

        if ($this->getNumPages() % 2 === 0){
            $this->LinearGradient($this->lMargin, $this->y, $wl, 1, $blue, $white, $coords);
        }
        else{
            $this->LinearGradient($this->lMargin, $this->y, $wl, 1, $white, $blue, $coords);
        }

        $this->fieldBookmarks();
    }

    public function Footer()
    {
        $this->SetY(-15);

        if($this->getNumPages() % 2 === 0){
            $this->showFooterEvenPage();
        }
        else{
            $this->showFooterOddPage();
        }
    }

    public function Output($name='doc.pdf', $dest='I')
    {
        parent::Output($name, $dest);
    }



    public function defaultSettingsPage()
    {
        $this->SetCreator('Alpha-Hydro');
        $this->SetAuthor('Alpha-Hydro');
        $this->SetTitle('Alpha-Hydro. Каталог товаров.');
        $this->SetSubject('Alpha-Hydro');
        $this->SetKeywords('Alpha-Hydro, PDF, каталог, гидравлика');

        // set header and footer fonts
        $this->setHeaderFont(array('arialnarrow', '', 12));
        $this->setFooterFont(array('arialnarrow', '', 12));

        // set default monospaced font
        $this->SetDefaultMonospacedFont('freeserif');

        // set margins
        $this->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $this->SetAutoPageBreak(TRUE, 20);

        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);


        // set booklet mode
        $this->SetBooklet(true, 15, 20);

        $this->SetFont('arialnarrow', '', 12, '', false);

        return $this;
    }

    public function introduction($html)
    {
        $this->SetHeaderData('', 0, 'Введение', '');
        $this->AddPage();
        $this->Bookmark('Введение', 0 , '', '', 'B', [237, 133, 31]);
        $this->writeHTML($html);
        $this->lastPage();

        return $this;
    }

    public function content(\SimpleXMLElement $xmlObject)
    {
        $xmlElements = $xmlObject->category;

        set_time_limit(1800);

        foreach ($xmlElements as $level1) {
            $this->setHeaderData('', 0, $level1->attributes()->name, '');
            $this->setImageFieldBookmark('fieldBookmark_'.(string) $level1->attributes()->id.'.png');
            $this->AddPage();
            $this->Bookmark($level1->attributes()->name, 0 , '', '', 'B', [237, 133, 31]);
            if($level1->category){
                foreach ($level1->category as $level2) {
                    $this->setHeaderData('', 0, $level2->attributes()->name, '');
                    $this->Bookmark($level2->attributes()->name, 1 , 0, '', 'B', [0, 0, 0]);
                    if($level2->category){
                        foreach ($level2->category as $level3) {
                            $this->Bookmark($level3->attributes()->name, 2 , 0, '', '', [0, 0, 0]);
                            //$this->Cell(0, 0, 'продуктов - '.count($level3->setProduct), 0, 1, 'L');
                            if(0 != count($level3->product)){
                                foreach ($level3->product as $item) {
                                    $this->setProduct($item);
                                }
                            }
                        }
                    }
                }
            }
        }

       //Debug::dump($this->outlines);die();

        $this->tableOfContent();
//
        return $this;
    }

    public function setProduct(\SimpleXMLElement $xmlElement)
    {
        $this->SetFont('arialnarrow', 'B', 16);
        $this->Cell(0, 0, $xmlElement->sku, 0, 1, 'L');

        $this->SetFontSize(12);
        //$this->SetDrawColor(237, 133, 31); //orange
        //$this->SetDrawColor(0, 141, 210); //blue
        $this->SetDrawColor(160,160,160); //gray
        $this->SetLineWidth(0.1);

        $this->Cell(0, 0, $xmlElement->name, 'B', 1, 'L');

        $this->Ln(5);

        $image_file = __DIR__ .'/../../../..'.$xmlElement->image->attributes()->path.(string) $xmlElement->image;
        $this->Image($image_file,$this->x,$this->y,'', 25, '', '', 'T');

        $this->SetX($this->x + 5);

        if($xmlElement->draft){
            $image_draft = __DIR__ .'/../../../..'.$xmlElement->draft->attributes()->path.(string) $xmlElement->draft;
            if(file_exists($image_draft))
                $this->Image($image_draft,$this->x,$this->y, '', 25, '', '', 'T',true,190);
            $this->SetX($this->x + 5);
        }

        $this->setProductProperty($xmlElement->properties->property);

        if ($this->y < $this->getImageRBY())
            $this->SetY($this->getImageRBY());
        $this->Ln(5);

        return $this;
    }

    /**
     * @param \SimpleXMLElement[] $productProperties
     * @return $this
     */
    public function setProductProperty($productProperties)
    {
        $x = $this->getImageRBX()+5;

        if (!empty($productProperties)){
            $w = array(30, $this->getPageWidth()-$this->original_rMargin-$x-30);
            foreach ($productProperties as $productProperty) {
                $this->SetFont('','B',8);
                $this->MultiCell($w[0], 0, $productProperty->attributes()->name, 0, 'L', false, 0, $x, '', true, 0, false, true, 0);

                $this->SetFont('','',8);
                $this->MultiCell($w[1], 0, str_replace(["\r\n", "\n", "\r"],'. ',$productProperty), 0, 'L', false, 0, '', '', true, 0, false, true, 0);

                $this->Ln();
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function tableOfContent()
    {
        $this->SetHeaderData('', 0, 'Содержание', '');
        $this->addTOCPage();
        $this->SetFont('arialnarrow', '', 12);
        $this->addTOC(2, 'arialnarrow', '.', 'Содержание', '', array(128,0,0));
        $this->endTOCPage();

        return $this;
    }

    /**
     * @param array $xmlElements
     * @return string
     */
    private function _getHtmlCategory($xmlElements)
    {
        $html = '<ul>';

        foreach ($xmlElements as $item) {
            $html .= '<li>'.$item->attributes()->name;
            if($item->category)
                $html .= $this->_getHtmlCategory($item->category);
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * @param int $widthWorkspacePage
     * @return PdfService
     */
    public function setWidthWorkspacePage($widthWorkspacePage)
    {
        $this->_widthWorkspacePage = $widthWorkspacePage;
        return $this;
    }

    /**
     * @param $image_field_bookmark
     * @return PdfService
     */
    public function setImageFieldBookmark($image_field_bookmark)
    {
        $image_path = __DIR__ .'/../../../../data/images/pdf/';
        $image = $image_path.$image_field_bookmark;

        $this->_image_field_bookmark = (file_exists($image))?$image: $image_path.'fieldBookmark.png';
        return $this;
    }

    /**
     * @return int
     */
    public function getWidthWorkspacePage()
    {
        return $this->_widthWorkspacePage;
    }

    /**
     * @return string
     */
    public function getImageFieldBookmark()
    {
        return $this->_image_field_bookmark;
    }

    /**
     * @return $this
     */
    private function fieldBookmarks(){
        $template_id = $this->startTemplate(14, 0, true);

        $image_file = $this->getImageFieldBookmark();
        if($this->getNumPages() % 2 === 0){
            $this->Image($image_file, 0, 0, 14, 0, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
        }
        else{
            $this->StartTransform();
            $this->MirrorH();
            $this->Image($image_file, -14, 0, 14, 0, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
            $this->StopTransform();
        }

        $this->endTemplate();

        $x = ($this->getNumPages() % 2 === 0)?$this->w - 13:0;

        $this->printTemplate($template_id, $x, 20, 0, 0, '', '', false);

        return $this;
    }

    /**
     * @return $this
     */
    private function showFooterEvenPage()
    {
        $image_file = __DIR__ .'/../../../../data/images/pdf/alfa-hydro.png';
        $this->Image($image_file, $this->original_lMargin, $this->y, 50, '', 'PNG', '', 'M', true, 150, '', false, false, 0, false, false, false);

        $this->SetFontSize(10);
        $this->SetXY($this->x + 3, $this->y + 1);
        $this->SetFillColor(228,228,228);
        $numberPageWith = 20;
        $this->Cell($this->getPageWidth() - $this->x - $numberPageWith - 3, 7, 'www.alpha-hydro.com', 0, 0, 'C', true, 'http://alpha-hydro.com/catalog', 0, false, 'M');
        $this->SetX($this->x + 3);

        //Номер страницы
        $this->setCellPaddings(5, 0, 0, 0);
        $this->SetFillColor(0,148,218);
        $this->SetTextColor(255);
        $this->SetFont('', 'B', 10);
        $this->Cell($numberPageWith, 7, $this->getAliasNumPage(), 0, 1, 'L', true, '', 0, false, 'M');

        return $this;
    }

    /**
     * @return $this
     */
    private function showFooterOddPage()
    {
        $this->SetFont('', 'B', 10);

        //Номер страницы
        $numberPageWith = 20;
        $this->SetXY(0, $this->y +6);
        $this->setCellPaddings(0, 0, 5, 0);
        $this->SetFillColor(0,148,218);
        $this->SetTextColor(255);
        $this->Cell($numberPageWith, 7, $this->getAliasNumPage(), 0, 0, 'R', true, '', 0, false, 'M');

        //Строка
        $this->SetX($this->x + 3);
        $this->SetFont('');
        $this->SetFillColor(228,228,228);
        $this->SetTextColor(0);
        $this->Cell($this->getPageWidth() - $this->x - $this->original_rMargin - 53, 7, 'www.alpha-hydro.com', 0, 0, 'C', true, 'http://alpha-hydro.com/catalog', 0, false, 'M');

        //Логотип
        $this->SetX($this->x +3);
        $image_file = __DIR__ .'/../../../../data/images/pdf/alfa-hydro.png';
        $this->Image($image_file, $this->x, $this->y -6, 50, '', 'PNG', '', 'M', true, 150, '', false, false, 0, false, false, false);

        return $this;
    }

}