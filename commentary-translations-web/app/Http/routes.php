<?php

header('Access-Control-Allow-Origin: *');

$router = app('router');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
|
| Here are the routes where it's publicly available.
|
*/

// Login and Logout
$router->resource('sessions', 'SessionsController', ['only' => ['store']]);
$router->get('/login', ['as' => 'sessions.create', 'uses' => 'SessionsController@create']);
$router->get('/logout', ['as' => 'sessions.destroy', 'uses' => 'SessionsController@destroy']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
|
| Here are the routes where it's protected and requires authentication available.
|
*/
$router->group(['middleware' => ['auth']], function ($router) {

    // Home or Main
    $router->get('/', ['as' => 'posts.index', 'uses' => 'PostsController@index']);

    // Post Model
    $router->resource('posts', 'PostsController', ['only' => ['show']]);

    /*
    |--------------------------------------------------------------------------
    | Application API
    |--------------------------------------------------------------------------
    |
    | JSON routes for AJAX based requests.
    |
    */
//     $router->group(['namespace' => 'Api', 'prefix' => 'api'], function ($router) {

//         // Users API
//         $router->get('/users/{limit}', ['uses' => 'UsersController@index']);

//     });
});

/*
|--------------------------------------------------------------------------
| API WEB SERVICE  ROUTES
|--------------------------------------------------------------------------
|
| Here are the api web service routes
|
*/
$router->group(['namespace' => 'Api', 'prefix' => 'api'], function ($router) {

    $router->group(['middleware' => ['api.session']], function ($router) {
        // Posts API Web Services
        $router->resource('posts', 'PostsController', ['only' => ['show']]);

//         // Disputes API Web Services
//         $router->resource('disputes', 'DisputesController', ['only' => ['store', 'index']]);

//         // Driver API Web Service
//         $router->resource('drivers', 'DriversController', ['only' => ['update']]);

//         // Reasons API
//         $router->get('/reasons', ['uses' => 'ReasonsController@listAll']);

//         // Confirmations API Web Services
//         $router->resource('confirmations', 'ConfirmationsController', ['only' => ['store', 'show']]);
    });

    $router->resource('sessions', 'SessionsController', ['only' => ['store']]);

});


