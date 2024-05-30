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

// Current User Route
Route::middleware(['auth:sanctum'])->get('/currentUser', function (Request $request) {
    return new UserResource(Auth::user());
});

// User Routes
Route::middleware('auth:sanctum')->group(function () { 
    Route::apiResources([
        'user' => UserController::class,
    ]);
    Route::post('rank', [RankController::class, 'store']);
    Route::get('question/{category_id}/{level_id}', [QuestionController::class, 'show']);
});

Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::get('subject/{type_id}/{exam_date_id}', [SubjectController::class, 'show']);
    Route::get('quesionList/{category_id}/{level_id}/{isGraduate}', [QuestionController::class, 'listQuestionAdmin']);

    Route::post('question', [QuestionController::class, 'store']);
    Route::put('question/{question}', [QuestionController::class, 'update']);
    Route::delete('question/{question}', [QuestionController::class, 'destroy']);

    Route::post('scholarship', [ScholarshipController::class, 'store']);
    Route::put('scholarship/{scholarship}', [ScholarshipController::class, 'update']);
    Route::delete('scholarship/{scholarship}', [ScholarshipController::class, 'destroy']);

    Route::post('type', [TypeController::class, 'store']);
    Route::put('type/{type}', [TypeController::class, 'update']);
    Route::delete('type/{type}', [TypeController::class, 'destroy']);

    Route::post('level', [LevelController::class, 'store']);
    Route::put('level/{level}', [LevelController::class, 'update']);
    Route::delete('level/{level}', [LevelController::class, 'destroy']);

    Route::post('subject', [SubjectController::class, 'store']);
    Route::put('subject/{subject}', [SubjectController::class, 'update']);
    Route::delete('subject/{subject}', [SubjectController::class, 'destroy']);

    Route::post('examDate', [ExamDateController::class, 'store']);
    Route::put('examDate/{examDate}', [ExamDateController::class, 'update']);
    Route::delete('examDate/{examDate}', [ExamDateController::class, 'destroy']);

    Route::post('category', [CategoryController::class, 'store']);
    Route::put('category/{category}', [CategoryController::class, 'update']);
    Route::delete('category/{category}', [CategoryController::class, 'destroy']);
});

// Public Routes
Route::get('level', [LevelController::class, 'index']);
Route::get('category', [CategoryController::class, 'index']);
Route::get('examDate', [ExamDateController::class, 'index']);
Route::get('type/{id}', [TypeController::class, 'show']);
Route::get('scholarship', [ScholarshipController::class, 'index']);
Route::get('pdf/{examdate_id}/{category_id}', [SubjectController::class, 'showPdf']);
Route::get('rank/{category_id}/{isGraduate}', [RankController::class, 'show']);
