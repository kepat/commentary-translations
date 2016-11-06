<?php
namespace App\Parsers;

use App\Contracts\Parsers\FileParserInterface;

/**
 * CSV Parser
 *
 * @package     App\Parsers
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/skd
 * @since       Class available since Release 0.1.0
 */
class CSVParser implements FileParserInterface
{

    /**
     * File to parse
     *
     * @access protected
     * @type   string
     */
    protected $file;


    /**
     * Load the file to be parsed
     *
     * @param  string $file
     *
     * @access public
     * @return boolean
     */
    public function load($file)
    {
        if (! file_exists($file)) {
            return false;
        }

        $this->file = $file;

        return true;
    }


    /**
     * Read the file contents
     *
     * @access public
     * @return string
     */
    public function read()
    {
        // Return array
        $data = [];

        // Read the whole csv file
        $contents = file_get_contents($this->file);

        // Trim first the string
        $contents = trim($contents);

        // Explode by new line for mac and window
        $results = preg_split('/\r\n|\r|\n/', $contents);

        // Go through all of the result (exploded new line)
        foreach ($results as $key => $result) {
            // Check if key is first then skip
            if ($key == 0) {
                continue;
            }
            // Separate the values and add it to the result
            array_push($data, str_getcsv($result));
        }

        return $data;
    }
}

