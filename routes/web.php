<?php

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
// Auth
Route::get('login', [
    'as' => 'login',
    'uses' => 'Auth\LoginController@showLoginForm'
]);
Route::post('login', [
    'as' => '',
    'uses' => 'Auth\LoginController@login'
]);
Route::post('logout', [
    'as' => 'logout',
    'uses' => 'Auth\LoginController@logout'
]);

Route::middleware('auth')->group(function () {
    Route::prefix('cms')->group(function () {
        Route::prefix('overview')->group(function () {
            Route::get('/', 'CMS\OverviewController@index');
            Route::get('update/{uid}/{page}', 'CMS\OverviewController@updateIndex');
            Route::post('update/{uid}/{page}', 'CMS\OverviewController@update');
            Route::get('ajax/overview', 'CMS\OverviewController@getDataAjax');
    
            Route::post('delete/photo/{uid}', 'CMS\OverviewController@deletePhoto');
            Route::post('ajax/delete-animal', 'CMS\OverviewController@deleteAnimalAjax');
        });
    
        Route::prefix('fauna')->group(function () {
            Route::get('create', 'CMS\FaunaController@index');
            Route::post('create', 'CMS\FaunaController@create');
        });
    
        Route::prefix('kml')->group(function () {
            Route::get('upload', 'CMS\KMLController@index');
            Route::post('upload', 'CMS\KMLController@create');
            Route::post('ajax/delete-kml', 'CMS\KMLController@deleteAjax');
            Route::get('ajax/upload-kml', 'CMS\KMLController@getDataAjax');
        });
    
        Route::prefix('map')->group(function () {
            Route::get('edit', 'CMS\MapController@index');
            Route::post('edit', 'CMS\MapController@update');
        });
    
        Route::prefix('user')->group(function () {
            Route::get('/', 'CMS\UserController@index');
            Route::post('/', 'CMS\UserController@create');
            Route::post('update', 'CMS\UserController@update');
            Route::get('ajax/get-user', 'CMS\UserController@getDataAjax');
        });
    
        Route::prefix('master')->group(function () {
            // Route::get('redlist-status', 'CMS\Master\RedlistStatusController@index'); // save for later
    
            Route::get('province', 'CMS\Master\ProvinceController@index');
            Route::post('province', 'CMS\Master\ProvinceController@create');
            Route::post('update-province', 'CMS\Master\ProvinceController@update');
            Route::get('ajax/province', 'CMS\Master\ProvinceController@getDataAjax');
    
            Route::get('conservation-status', 'CMS\Master\ConservationStatusController@index');
            Route::post('conservation-status', 'CMS\Master\ConservationStatusController@create');
            Route::post('update-conservation-status', 'CMS\Master\ConservationStatusController@update');
            Route::get('ajax/conservation-status', 'CMS\Master\ConservationStatusController@getDataAjax');
    
            Route::get('kingdom', 'CMS\Master\KingdomController@index');
            Route::post('kingdom', 'CMS\Master\KingdomController@create');
            Route::post('update-kingdom', 'CMS\Master\KingdomController@update');
            Route::get('ajax/kingdom', 'CMS\Master\KingdomController@getDataAjax');
    
            Route::get('phylum', 'CMS\Master\PhylumController@index');
            Route::post('phylum', 'CMS\Master\PhylumController@create');
            Route::post('update-phylum', 'CMS\Master\PhylumController@update');
            Route::get('ajax/phylum', 'CMS\Master\PhylumController@getDataAjax');
    
            Route::get('class', 'CMS\Master\ClassController@index');
            Route::post('class', 'CMS\Master\ClassController@create');
            Route::post('update-class', 'CMS\Master\ClassController@update');
            Route::get('ajax/class', 'CMS\Master\ClassController@getDataAjax');
    
            Route::get('ordo', 'CMS\Master\OrdoController@index');
            Route::post('ordo', 'CMS\Master\OrdoController@create');
            Route::post('update-ordo', 'CMS\Master\OrdoController@update');
            Route::get('ajax/ordo', 'CMS\Master\OrdoController@getDataAjax');
    
            Route::get('family', 'CMS\Master\FamilyController@index');
            Route::post('family', 'CMS\Master\FamilyController@create');
            Route::post('update-family', 'CMS\Master\FamilyController@update');
            Route::get('ajax/family', 'CMS\Master\FamilyController@getDataAjax');
    
            Route::get('genus', 'CMS\Master\GenusController@index');
            Route::post('genus', 'CMS\Master\GenusController@create');
            Route::post('update-genus', 'CMS\Master\GenusController@update');
            Route::get('ajax/genus', 'CMS\Master\GenusController@getDataAjax');
    
        });
    });
});


//front end
Route::get('/', 'APP\IndexController@index');
Route::get('ajax/province', 'APP\IndexController@getDataAjaxProvince');
Route::get('ajax/animals', 'APP\IndexController@getDataAjaxAnimals');
Route::get('ajax/modalImage', 'APP\IndexController@getDataAjaxModalImage');
Route::get('ajax/modalVideo', 'APP\IndexController@getDataAjaxModalVideo');
Route::get('ajax/detail', 'APP\IndexController@getDataAjaxDetail');
Route::get('ajax/zoomIn', 'APP\IndexController@getDataAjaxZoomIn');
Route::get('ajax/kml', 'APP\IndexController@getDataAjaxKml');


