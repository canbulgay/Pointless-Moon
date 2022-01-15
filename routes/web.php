<?php

use Illuminate\Support\Facades\Route;
use Shopify\Clients\Rest as ShopifyAPI;
use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

Route::get('shopifytest', function (ShopifyAPI $client) {

    $response = $client->get('products');
    return $response->getDecodedBody();
});

Route::get('translatetest', function (TranslateClient $translate) {
    
    $result = $translate->translate(
        'Convert Daily Grasshoper'
    );

    return $result['text'];
});

Route::get('exceltest', function () {
    //  Get Excel file
    //  Show a cell of each line on the screen
    $reader = IOFactory::createReaderForFile(Storage::path('demo.xls'));
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load(Storage::path('demo.xls'));

    $text = "";
    for ($i = 4; $i < 104; $i++) {
        $text .= $spreadsheet->getActiveSheet()->getCell('E' . $i) . "<br>";
    }

    return $text;
});

