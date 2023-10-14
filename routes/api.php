<?php


use App\Http\Controllers\API\LearnController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\API\ArticleController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\CommentController;

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
Route::post('/generate-topic', [LearnController::class, 'generateTopic']);

Route::get('artikels', [ArticleController::class, 'index']);
Route::post('artikels', [ArticleController::class, 'store']);
Route::get('artikels/{id}', [ArticleController::class, 'show']);
Route::put('artikels/{id}', [ArticleController::class, 'update']);
Route::delete('artikels/{id}', [ArticleController::class, 'destroy']);

Route::post('comments', [CommentController::class, 'store']);
Route::post('reactions', [ReactionController::class, 'store']);

Route::middleware(['auth:sanctum','verified'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [NewPasswordController::class, 'reset']);

Route::post('update-profile', [ProfileController::class, 'update_profile'])->middleware('auth:sanctum');
