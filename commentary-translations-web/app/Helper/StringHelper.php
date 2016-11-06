<?php namespace App\Helper;

/**
 * Contains all the helper function for strings
 *
 * @package     App
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/scs
 * @since       Class available since Release 0.1.0
 */
class StringHelper
{

    /**
     * Apply something similar to like of mysql,
     * uses regular expression.
     *
     * @param String $pattern
     * @param String $value
     *
     * @return boolean
     *
     */
    public static function like($pattern, $value)
    {
        // Prepare the patter for the regular expression
        $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));

        // Check if it is a match
        return (bool)preg_match("/^{$pattern}$/i", $value);
    }

}