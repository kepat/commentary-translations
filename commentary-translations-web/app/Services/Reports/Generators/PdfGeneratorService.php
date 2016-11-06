<?php
namespace App\Services\Reports\Generators;

use Auth;
use Knp\Snappy\Pdf as SnappyPdf;
use App\Contracts\Services\Reports\GeneratorInterface;

/**
 * Pdf Report Generation.
 *
 * @package     App\Report\Generators
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/skd
 * @since       Class available since Release 0.1.0
 */
class PdfGeneratorService implements GeneratorInterface
{

    /**
     * Pdf generator instance.
     *
     * @access protected
     * @type   SnappyPdf
     */
    protected $pdf;


    /**
     * Create a PDF instance
     *
     * @param  SnappyPdf $pdf
     *
     * @access public
     */
    public function __construct(SnappyPdf $pdf)
    {
        $this->pdf = $pdf;
    }


    /**
     * Genearate a report.
     *
     * @param  string $name
     * @param  string $content
     * @param  array  $options
     *
     * @access public
     * @return bool
     */
    public function generate($name, $content, $options = [])
    {
        $replacement = [
            'footer' => 'footer-left'
        ];

        $email   = str_replace(['.', '@'], '', Auth::user()->email);
        $path    = public_path() . '/files/' . $email . '/' . $name . '.pdf';
        $options = array_combine(array_merge($options, $replacement), $options);
        $options = $this->options($options);

        $this->pdf->setOptions($options);
        $this->pdf->generateFromHtml($content, $path, [], true);

        return true;
    }


    protected function options($parameters = [])
    {
        $defaults = [
            'margin-top'       => '15',
            'margin-bottom'    => '15',
            'images'           => true,
            'footer-line'      => true,
            'footer-right'     => 'Page [page] of [topage]',
            'footer-font-size' => '8',
            'footer-font-name' => 'Helvetica Neue',
        ];

        return array_merge($defaults, $parameters);
    }
}