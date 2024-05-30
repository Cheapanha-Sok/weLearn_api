<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExamDateController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/currentUser', function (Request $request) {
    return new UserResource(Auth::user());
});

// User Route
Route::middleware('auth:sanctum')->group(function () { // user route
    Route::apiResources([
        'user' => UserController::class,
    ]);
    Route::post('rank', [RankController::class, 'store']);
    Route::get('question/{category_id}/{level_id}', [QuestionController::class, 'show']);

});
// Admin Route
Route::middleware('auth:sanctum')->middleware(AdminMiddleware::class)->group(function () {
    Route::post('category', [CategoryController::class, 'store']);
    Route::put('category/{id}', [CategoryController::class, 'update']);
    Route::delete('category/{id}', [CategoryController::class, 'destroy']);

    Route::post('examDate', [ExamDateController::class, 'store']);
    Route::put('examDate/{id}', [ExamDateController::class, 'update']);
    Route::delete('examDate/{id}', [ExamDateController::class, 'destroy']);

    Route::post('subject', [SubjectController::class, 'store']);
    Route::put('subject/{id}', [SubjectController::class, 'update']);
    Route::delete('subject/{id}', [SubjectController::class, 'destroy']);

    Route::post('level', [LevelController::class, 'store']);
    Route::put('level/{id}', [LevelController::class, 'update']);
    Route::delete('level/{id}', [LevelController::class, 'destroy']);

    Route::post('type', [TypeController::class, 'store']);
    Route::put('type/{id}', [TypeController::class, 'update']);
    Route::delete('type/{id}', [TypeController::class, 'destroy']);

    Route::post('scholarship', [ScholarshipController::class, 'update']);
    Route::delete('scholarship/{id}', [ScholarshipController::class, 'destroy']);

    Route::post('question', [QuestionController::class, 'store']);
    Route::put('question/{id}', [QuestionController::class, 'update']);
    Route::delete('question/{id}', [QuestionController::class, 'destroy']);
    Route::get('quesionList/{category_id}/{level_id}/{isGraduate}', [QuestionController::class, 'listQuestionAdmin']);
});
// Public Route
Route::group([], function () {
    Route::get('level', [LevelController::class, 'index']);
    Route::get('category', [CategoryController::class, 'index']);
    Route::get('examDate', [ExamDateController::class, 'index']);
    Route::get('type/{id}', [TypeController::class, 'show']);
    Route::get('scholarship', [ScholarshipController::class, 'index']);
    Route::get('subject/{type_id}/{exam_date_id}', [SubjectController::class, 'show']);
    Route::get('pdf/{examdate_id}/{category_id}', [SubjectController::class, 'showPdf']);
    Route::get('rank/{category_id}/{isGraduate}', [RankController::class, 'show']);
});