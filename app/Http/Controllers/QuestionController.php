<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Choice;
use App\Models\Question;
use App\Models\UserQuestion;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
        $questions = Question::where('isGraduate', $indentityUser)
            ->with('choices')
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


    public function edit(QuestionRequest $request, Question $question)
    {
        $validated = $request->validated();
        $question->update($validated);
        return $this->sendSuccess([$question], "updated question successful");

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
