<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExamDateController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/currentUser', function (Request $request) {
    return Auth::user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'user' => UserController::class,
        'question' => QuestionController::class,
        'rank' => RankController::class
    ]);
    Route::get('question/{category_id}/{level_id}', [QuestionController::class, 'show']);
});

Route::apiResource('category', CategoryController::class);
Route::apiResource('examDate', ExamDateController::class);
Route::apiResource('subject', SubjectController::class);
Route::apiResource('level', LevelController::class);
Route::apiResource('type', TypeController::class);
Route::get('pdf/{examdate_id}/{category_id}', [SubjectController::class, 'show']);
Route::get('subject/{type_id}', [SubjectController::class, 'showByType']);


