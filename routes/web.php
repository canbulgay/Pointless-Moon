<?php

use Illuminate\Support\Facades\Route;
use Shopify\Auth\FileSessionStorage;
use Shopify\Context;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('shopify', function () {
    Context::initialize(
        config('services.shopify.app_key'),
        config('services.shopify.app_password'),
        config('services.shopify.app_scopes'),
        config('services.shopify.app_host'),
        new FileSessionStorage('/tmp/php_sessions'),
        config('services.shopify.api_version'),
        false,
        true
    );
    
});
});