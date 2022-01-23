<?php

use Illuminate\Support\Facades\Route;
use Shopify\Clients\Rest as ShopifyAPI;
use App\Helpers\TranslateHelper as TranslateClient;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use App\Http\Controllers\TranslationController;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('shopifytest', function (ShopifyAPI $client) {

    $response = $client->get('products');
    return $response->getDecodedBody();
});

Route::get('translatetest', function (TranslateClient $translate) {
    
    return $translate->translate('Hello World!');
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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::resource('translations',TranslationController::class)
    ->only(['index','update'])
    ->middleware(['auth:sanctum', 'verified']);

Route::middleware(['auth:sanctum','verified'])->get('import-excel',function(){
    return Inertia::render('ImportExcel');
})->name('import-excel');