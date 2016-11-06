<?php
namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Returns the json data format
     *
     * @param integer $status
     * @param array  $data
     * @param string $message
     *
     * @access public
     * @return json
     */
    public function jsonResult($status, $data, $message)
    {
        return ['status' => $status, 'data' => $data, 'message' => $message];
    }
}