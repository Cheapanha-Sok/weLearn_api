<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Choice;
use App\Models\Question;
use App\Models\UserQuestion;
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

        return $this->sendSuccess(QuestionResource::collection($randomTenQuestion), "fetch question list");
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
    public function destroy(Question $question)
    {
        $question->delete();
        return $this->sendSuccess([], "question remove sucessful");
    }
    public function store(QuestionRequest $request)
    {
        $validated = $request->validated();
        $question = Question::create($validated);

        // Prepare choices data
        $choicesData = $validated['choices'];
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
        return $this->sendSuccess('question create successfully');
    }

    private function saveChoice($choices)
    {
        Choice::insert($choices);
    }


}
