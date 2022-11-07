<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Auth;
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
    return redirect('home');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/employees', 
    [EmployeeController::class, 'index']
)->name('employees.index')
->middleware('auth');

Route::post('/employees/upload',
    [EmployeeController::class, 'upload']
)->name('employees.upload')
->middleware('auth');

Route::get('/companies', 
    [CompanyController::class, 'index']
)->name('companies.index')
->middleware('auth');

Auth::routes();