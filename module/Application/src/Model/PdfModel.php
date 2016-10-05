<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Application\Model;

use Zend\View\Model\ViewModel;

class PdfModel extends ViewModel
{
    protected $options = [
        'paperSize' => '8x11',
        'paperOrientation' => 'portrait',
        'basePath' => '/',
        'fileName' => null
    ];

    /**
     * PDF probably won't need to be captured into a
     * a parent container by default.
     *
     * @var string
     */
    protected $captureTo = null;

    /**
     * PDF is usually terminal
     *
     * @var bool
     */
    protected $terminate = true;
}