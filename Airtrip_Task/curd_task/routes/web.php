<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->post('adddepartment', 'WorkController@adddepartment');
    $router->post('updatedepartment', 'WorkController@updatedepartment');
    $router->get('viewdepartment', 'WorkController@viewdepartment');
    $router->post('deletedepartment', 'WorkController@deletedepartment');
    $router->get('searchemp', 'WorkController@searchuser');
    $router->get('addemp', 'WorkController@addemp');
    $router->get('viewemp', 'WorkController@viewemp');

});
