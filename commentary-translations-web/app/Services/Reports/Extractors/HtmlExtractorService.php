<?php
namespace App\Services\Reports\Extractors;

use Auth;
use App\Contracts\Services\Reports\ExtractorInterface;

/**
 * Extract the data from the html pages.
 *
 * @package     App\Report\Extractors
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/skd
 * @since       Class available since Release 0.1.0
 */
class HtmlExtractorService implements ExtractorInterface
{

    /**
     * Save the extracted data to a file
     *
     * @param  string $route
     *
     * @access public
     *
     * @return string $path
     */
    public function save($route)
    {
        $email     = str_replace(['.', '@'], '', Auth::user()->email);
        $directory = public_path() . "/files/" . $email . "/";
        $name      = str_replace('/', '-', substr(strstr($route, '//'), 2));
        $path      = $directory . "{$name}.html";

        if (! file_exists($directory)) {
            mkdir($directory, 0777);
        }

        $content = file_get_contents($route);
        file_put_contents($path, $content);

        return $path;
    }


    /**
     * Extract the content of the html file
     *
     * @param  string $route
     *
     * @access public
     *
     * @return string $content
     */
    public function extract($route)
    {
        // Create the html file and get the file path
        $path = $this->save($route);

        // Store the content of the file
        $return = file_get_contents($path);

        // Delete the file
        unlink($path);

        return $return;
    }
}