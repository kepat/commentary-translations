<?php
namespace App\Parsers\Binders;

use PHPExcel_Cell;
use PHPExcel_Cell_IValueBinder;
use PHPExcel_Cell_DefaultValueBinder;

/**
 * Excel Value Binder
 *
 * @package     App\Parsers\Binders
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/scs
 * @since       Class available since Release 0.1.0
 */
class ExcelValueBinder extends PHPExcel_Cell_DefaultValueBinder implements PHPExcel_Cell_IValueBinder
{

    /**
     * Value data type
     *
     * @access protected
     * @type   string
     */
    protected $data_type;


    /**
     * Constructor of the class
     *
     * @param string $data_type
     *
     * @access public
     */
    public function __construct($data_type)
    {
        // Available data types TYPE_STRING, TYPE_FORMULA, TYPE_NUMERIC, TYPE_BOOL, TYPE_NULL, TYPE_INLINE and TYPE_ERROR
        $this->data_type = $data_type;
    }


    /**
     * Bind value
     *
     * @param PHPExcel_Cell $cell
     * @param mixed         $value
     *
     * @access public
     * @return mixed
     */
    public function bindValue(PHPExcel_Cell $cell, $value = null)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, constant("PHPExcel_Cell_DataType::$this->data_type"));

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}

