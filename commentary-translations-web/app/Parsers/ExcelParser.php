<?php
namespace App\Parsers;

use PHPExcel_Cell;

use App\Contracts\Parsers\FileParserInterface;
use App\Parsers\Binders\ExcelValueBinder;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Excel Parser
 *
 * @package     App\Parsers
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/scs
 * @since       Class available since Release 0.1.0
 */
class ExcelParser implements FileParserInterface
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
     * @param integer $sheet_index
     *
     * @access public
     * @return array
     */
    public function read($sheet_index = 0)
    {
        // Return array
        $data = [];

        // Call the value binder for the excel
        $value_binder = app(ExcelValueBinder::class, ['TYPE_STRING']);

        // Get the LaravelExcelReader
        $reader = Excel::setValueBinder($value_binder)->noHeading(true)->setSelectedSheetIndices([$sheet_index])->load($this->file);

        // Getting all results
        $results = $reader->all();

        // Store the result to an array
        foreach ($results as $key => $result) {
            // Check if key is first then skip
            if ($key == 0) {
                continue;
            }

            array_push($data, $result->toArray());
        }

        return $data;
    }

}

