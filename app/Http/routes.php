<?php

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

Route::get('/', function () {
    $cbr = new \App\Services\Exchange\Providers\CbrProvider(
        new \App\Services\Exchange\Providers\Cbr\CbrRequest(),
        new \App\Services\Exchange\Providers\Cbr\Parser(new \Sabre\Xml\Service())
        );

    echo"<pre>";print_r($cbr->getRateValues('EUR')); echo"</pre>";die;
    //$cbr->getRateValues();
    return view('welcome');
});
