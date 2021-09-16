<?php

use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\BudgetsController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('register', [AuthController::class, 'register']);

});

Route::resource('/expenses', ExpensesController::class)->middleware('auth');
Route::resource('/budgets', BudgetsController::class)->middleware('auth');
Route::post('/expenses/getExpensesByDate', [ExpensesController::class,'getExpensesByDate']);