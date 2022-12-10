<?php

use App\Jobs\ValidedUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/{any}',function(Request $request){
    // ValidedUser::dispatch($request);
    // DB::table('personal_access_tokens')->truncate();
    return view('spa');
})->where('any','.*');