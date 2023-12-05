<?php


use App\Http\Controllers\API\LearnController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\API\ArticleController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\CreditPackageController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\ReactionController;
use App\Http\Controllers\CommentController as ControllersCommentController;
use Illuminate\Routing\Route as RoutingRoute;

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


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/generate-topic', [LearnController::class, 'generateTopic']);

    Route::get('artikels', [ArticleController::class, 'index']);
    Route::post('artikels', [ArticleController::class, 'store']);
    Route::get('artikels/{id}', [ArticleController::class, 'show']);
    Route::put('artikels/{id}', [ArticleController::class, 'update']);
    Route::delete('artikels/{id}', [ArticleController::class, 'destroy']);

    Route::post('comments', [CommentController::class, 'store']);
    Route::post('reactions', [ReactionController::class, 'store']);

    Route::post('/update-profile', [ProfileController::class, 'update_profile'])->middleware('auth:sanctum');

    Route::post('/chat', [ChatController::class, 'createChat']);
    Route::get('/chat/{from}', [ChatController::class, 'getChatForUser']);

    Route::get('/credit-packages', [CreditPackageController::class, 'getCreditPackages']);
    Route::get('/credit-packages/{id}', [CreditPackageController::class, 'getCreditPackageById']);

    Route::post('transaction/create-payment', [TransactionController::class, 'createPayment'])->name('transaction.create-payment');
    Route::post('transaction/webhook', [TransactionController::class, 'webhook'])->name('transaction.webhook');

    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login'])->middleware('guest:sanctum');
Route::post('register', [AuthController::class, 'register']);

Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [NewPasswordController::class, 'reset']);
