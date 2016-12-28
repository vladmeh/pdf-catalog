<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Pdf\Service;


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
        $this->writeHTML($html);
        $this->lastPage();

        return $this;
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
     * @param string $image_field_bookmark
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