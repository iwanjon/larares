<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post("/user",[UserController::class, "register"]);
Route::post("/user/login",[UserController::class, "login"]);

Route::get("/campaigns",[CampaignController::class, "getCampaigns"]);
Route::get("/campaign/{id}",[CampaignController::class, "getCampaign"]);

Route::get("/campaign/{id}/transactions",[TransactionController::class,"GetCampaignTransactions"]);

Route::middleware(ApiAuthMiddleware::class)->group(function(){
    Route::get("/user/current",[UserController::class, "get"]);
    Route::patch("/user/current",[UserController::class, "patch"]);
    Route::delete("/user/logout",[UserController::class, "logout"]);
    
    Route::post("/campaign",[CampaignController::class,"createCampaign"]);
    Route::patch("/campaign/{id}",[CampaignController::class,"updateCampaign"]);
    Route::post('/campaign/{id}/upload/image', [CampaignController::class, 'uploadCampaignImage']);

    Route::post("/campaign/{id}/transaction",[TransactionController::class,"CreateTransaction"]);
    Route::get("/user/{id}/transactions",[TransactionController::class,"GetUserTransactions"]);
});
Route::post('midtrans/notification', [TransactionController::class, 'Notification']);