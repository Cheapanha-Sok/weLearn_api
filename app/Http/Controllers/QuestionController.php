<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Choice;
use App\Models\Question;
use App\Models\UserQuestion;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends BaseController
{
    public function show($categoryId, $levelId)
    {
        $user = Auth::user();
        $indentityUser = $user->isGraduate;

        // Fetch question IDs that the user has already completed
        $completedQuestionIds = UserQuestion::where('user_id', $user->id)
            ->pluck('question_id')
            ->toArray();

        // Fetch questions excluding the completed ones
        $questions = Question::with('choices')
            ->where('isGraduate', $indentityUser)
            ->whereHas('level', function (Builder $query) use ($levelId) {
                $query->where('id', $levelId);
            })
            ->whereHas('category', function (Builder $query) use ($categoryId) {
                $query->where('id', $categoryId);
            })
            ->whereNotIn('id', $completedQuestionIds) // Exclude completed questions
            ->get();

        // Shuffle and take 10 questions
        $randomTenQuestion = $questions->shuffle()->take(10)->values();

        return $this->sendResponse(QuestionResource::collection($randomTenQuestion), "fetch question list");
    }


    public function edit(Request $request, $id)
    {
        $category = Question::find($id);
        if ($category != null) {
            if ($request->input('name') != "") {
                $category->name = $request->input("name");
                $category->save();
                return response()->json(['message' => 'category updated successfully'], 200);
            }
        }
        return response()->json(['message' => "categories with id $id not found"], 404);

    }
    public function destroy($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->delete();
            return $this->sendMessage("question with id $id remove sucessful");
        } catch (Exception $e) {
            return $this->sendError($e, "Something when wrong during delete question");
        }
    }
    public function store(QuestionRequest $request)
    {
        try {
            // Validate the request
            $request->validated();

            // Create the question
            $question = Question::create([
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'level_id' => $request->input('level_id'),
                'isGraduate' => $request->input('isGraduate')
            ]);

            // Prepare choices data
            $choicesData = $request->input('choices');
            $choices = [];
            foreach ($choicesData as $choice) {
                $choices[] = [
                    'name' => $choice['name'],
                    'is_correct' => $choice['is_correct'],
                    'question_id' => $question->id,
                ];
            }
            // Save the choices
            $this->saveChoice($choices);
            return $this->sendMessage('Question created successfully');
        } catch (Exception $e) {
            return $this->sendError('Something went wrong during create question', 500, $e->getMessage());
        }
    }

    private function saveChoice($choices)
    {
        try {
            // Insert the choices into the database
            Choice::insert($choices);
        } catch (Exception $e) {
            throw new Exception('Something went wrong during save choice: ' . $e->getMessage(), 500);
        }
    }


}
