<?php

use Illuminate\Support\Facades\Route;
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

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('table-list', function () {
        return view('pages.table_list');
    })->name('table');

    Route::get('notifications', function () {
        return view('pages.notifications');
    })->name('notifications');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
    //admin relation
    Route::get('relation', ['as' => 'relation.index', 'uses' => 'App\Http\Controllers\RelationController@index']);
    Route::get('relation/get', ['as' => 'relation.getUser', 'uses' => 'App\Http\Controllers\RelationController@getUser']);
    Route::get('relation/cusers/{id}', ['as' => 'relation.getCUser', 'uses' => 'App\Http\Controllers\RelationController@getCUser']);
    Route::post('relation/addUsers/{id}', ['as' => 'relation.addUsers', 'uses' => 'App\Http\Controllers\RelationController@addUsers']);
    Route::post('relation/delUsers/{id}', ['as' => 'relation.delUsers', 'uses' => 'App\Http\Controllers\RelationController@delUsers']);

    //admin status
    Route::get('status', ['as' => 'status.index', 'uses' => 'App\Http\Controllers\StatusController@index']);
    Route::get('statusdetail/{id}', ['as' => 'status.detail', 'uses' => 'App\Http\Controllers\StatusController@detail']);
    Route::post('statusdetail/{id}', ['as' => 'status.userlogs', 'uses' => 'App\Http\Controllers\StatusController@userlogs']);
    Route::post('editstatus' , ['as' => 'status.editstatus' , 'uses' => 'App\Http\Controllers\StatusController@editstatus']) ;
    Route::post('save_registries' , ['as' => 'status.save_registries' , 'uses' => 'App\Http\Controllers\StatusController@save_registries']);
    Route::post('delete_registry' , ['as' => 'status.delete_registry' , 'uses' => 'App\Http\Controllers\StatusController@delete_registry']) ;
    Route::post('analysis_registry' , ['as' => 'status.analysis_registry' , 'uses' => 'App\Http\Controllers\StatusController@analysis_registry']) ;

    //subadmin mystatus
    Route::get('mystatus', ['as' => 'mystatus.index', 'uses' => 'App\Http\Controllers\MyStatusController@index']);
    Route::post('save_status', 'App\Http\Controllers\MyStatusController@save_status');

    //subadmin myschedule
    Route::get('myschedule', ['as' => 'myschedule.index', 'uses' => 'App\Http\Controllers\MyScheduleController@index']);

    Route::post('myschedule/getDataYear', ['as' => 'myschedule.getDataYear', 'uses' => 'App\Http\Controllers\MyScheduleController@getDataYear']);
    Route::post('myschedule/getOneYear', ['as' => 'myschedule.getOneYear', 'uses' => 'App\Http\Controllers\MyScheduleController@getOneYear']);
    Route::post('myschedule/saveOneYear', ['as' => 'myschedule.saveOneYear', 'uses' => 'App\Http\Controllers\MyScheduleController@saveOneYear']);
    Route::post('myschedule/deleteOneYear', ['as' => 'myschedule.deleteOneYear', 'uses' => 'App\Http\Controllers\MyScheduleController@deleteOneYear']);
    Route::post('myschedule/addOneYear', ['as' => 'myschedule.addOneYear', 'uses' => 'App\Http\Controllers\MyScheduleController@addOneYear']);


    Route::post('myschedule/getDataMonth', ['as' => 'myschedule.getDataMonth', 'uses' => 'App\Http\Controllers\MyScheduleController@getDataMonth']);
    Route::post('myschedule/getOneMonth', ['as' => 'myschedule.getOneMonth', 'uses' => 'App\Http\Controllers\MyScheduleController@getOneMonth']);
    Route::post('myschedule/saveOneMonth', ['as' => 'myschedule.saveOneMonth', 'uses' => 'App\Http\Controllers\MyScheduleController@saveOneMonth']);
    Route::post('myschedule/deleteOneMonth', ['as' => 'myschedule.deleteOneMonth', 'uses' => 'App\Http\Controllers\MyScheduleController@deleteOneMonth']);
    Route::post('myschedule/addOneMonth', ['as' => 'myschedule.addOneMonth', 'uses' => 'App\Http\Controllers\MyScheduleController@addOneMonth']);

    //subadmin mylogs
    Route::get('mylogs', ['as' => 'mylogs.index', 'uses' => 'App\Http\Controllers\MyLogsController@index']);
    Route::post('mylogs', ['as' => 'mylogs.getData', 'uses' => 'App\Http\Controllers\MyLogsController@getData']);

    //subadmin memebers
    Route::get('mymembers', ['as' => 'mymembers.index', 'uses' => 'App\Http\Controllers\MyMembersController@index']);
    Route::get('mymembersdetail/{id}', ['as' => 'mymembers.detail', 'uses' => 'App\Http\Controllers\MyMembersController@detail']);
    Route::post('mymembersdetail/{id}', ['as' => 'mymembers.memberlogs', 'uses' => 'App\Http\Controllers\MyMembersController@memberlogs']);

    //admin category
    Route::get('category', ['as' => 'categories.index', 'uses' => 'App\Http\Controllers\CategoryController@index']);
    Route::post('category', ['as' => 'categories.store', 'uses' => 'App\Http\Controllers\CategoryController@store']);
    Route::put('category/{id}', ['as' => 'categories.update', 'uses' => 'App\Http\Controllers\CategoryController@update']);

    //admin moneylog
    Route::get('moneylog', ['as' => 'moneylogs.index', 'uses' => 'App\Http\Controllers\MoneyLogController@index']);
    Route::post('moneylog', ['as' => 'moneylogs.store', 'uses' => 'App\Http\Controllers\MoneyLogController@store']);
    Route::put('moneylog/{id}', ['as' => 'moneylogs.update', 'uses' => 'App\Http\Controllers\MoneyLogController@update']);
    Route::post('moneylogdetail', ['as' => 'moneylogs.detail', 'uses' => 'App\Http\Controllers\MoneyLogController@getDetail']);

    //admin customer
    Route::get('customer', ['as' => 'customers.index', 'uses' => 'App\Http\Controllers\CustomerController@index']);
    Route::post('customer', ['as' => 'customers.store', 'uses' => 'App\Http\Controllers\CustomerController@store']);
    Route::put('customer/{id}', ['as' => 'customers.update', 'uses' => 'App\Http\Controllers\CustomerController@update']);
    Route::get('customer/detail/{id}', ['as' => 'customers.detail', 'uses' => 'App\Http\Controllers\CustomerController@detail']);

    //admin projects
    Route::get('projects', ['as' => 'projects.index', 'uses' => 'App\Http\Controllers\ProjectController@index']);
    Route::post('projects', ['as' => 'projects.store', 'uses' => 'App\Http\Controllers\ProjectController@store']);
    Route::put('projects/{id}', ['as' => 'projects.update', 'uses' => 'App\Http\Controllers\ProjectController@update']);

    //profile
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});
