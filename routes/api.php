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
        'category' => CategoryController::class,
        'examDate' => ExamDateController::class,
        'subject' => SubjectController::class,
        'level' => LevelController::class,
        'type' => TypeController::class, 
        'scholarship' =>SubjectController::class,
        'question' => QuestionController::class

    ]);
    Route::post('rank', [RankController::class, 'store']);
    Route::get('question/{category_id}/{level_id}', [QuestionController::class, 'show']);
    Route::get('quesionList/{category_id}/{level_id}/{isGraduate}', [QuestionController::class, 'listQuestionAdmin']);
    Route::get('subject/{type_id}/{exam_date_id}', [SubjectController::class, 'show']);
});

Route::get('level', [LevelController::class, 'index']);
Route::get('category', [CategoryController::class, 'index']);
Route::get('examDate', [ExamDateController::class, 'index']);
Route::get('type/{id}', [TypeController::class, 'show']);
Route::get('scholarship', [ScholarshipController::class, 'index']);
Route::get('pdf/{examdate_id}/{category_id}', [SubjectController::class, 'showPdf']);
Route::get('rank/{category_id}/{isGraduate}', [RankController::class, 'show']);
