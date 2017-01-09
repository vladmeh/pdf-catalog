<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Pdf\Service;


interface PdfServiceInterface
{
    public function Output($name='doc.pdf', $dest='I');

    public function defaultSettingsPage();

    public function introduction($html);

    public function content(\SimpleXMLElement $xmlObject);
}