<?php

if (! function_exists('is_associative')) {

    /**
     * Check if the given array is associative.
     *
     * @param  array  $array
     * @return boolean
     */
    function is_associative(array $array)
    {
        $keys = array_keys($array);

        return (boolean) array_filter($keys, 'is_string');
    }
}
