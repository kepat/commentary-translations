<?php
namespace App\Http\Controllers\Api;

use App\Translation;
use Illuminate\Http\Request;

class PostsController extends Controller
{

    /**
     * Displays all the list of post
     *
     * @return \Illuminate\Http\Request
     */
    public function show($id, Request $request)
    {

        // Get the requested data
        $data = Translation::find($id);

        return $this->jsonResult('0', $data, 'success');
    }

}