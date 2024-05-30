<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Choice;
use App\Models\Question;
use App\Models\UserQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class QuestionController extends BaseController
{
    private function getUserIdentity()
    {
        return Auth::user();

    }
    public function show($categoryId, $levelId)
    {
        $user = $this->getUserIdentity();
        $completedQuestionIds = $this->getUserCompleteQuestion();
        $questions = Question::where('is_graduate', $user->is_graduate)
            ->with('choices')
            ->where('category_id', $categoryId)
            ->where('level_id', $levelId)
            ->whereNotIn('id', $completedQuestionIds)
            ->get();
        $randomTenQuestion = $questions->shuffle()->take(10)->values();

        return $this->sendSuccess(QuestionResource::collection($randomTenQuestion), "fetch question list");
    }

    private function getUserCompleteQuestion()
    {
        $user = $this->getUserIdentity();
        return UserQuestion::where('user_id', $user->id)
            ->pluck('question_id')
            ->toArray();
    }


    public function listQuestionAdmin($categoryId, $levelId, $isGraduate)
    {
        $questions = Question::
            with('choices')
            ->where('category_id', $categoryId)->where('level_id', $levelId)->where('is_graduate', $isGraduate)
            ->get();
        return $this->sendSuccess(QuestionResource::collection($questions), "fetch question list");

    }
    public function update(QuestionRequest $request, Question $question)
    {

        $request->validated();
        $question->update([
            ...$request->except('choices')
        ]);
        $choicesData = $request->input('choices', []);

        // Update choices
        foreach ($choicesData as $choiceData) {
            if (isset($choiceData['id'])) {
                // If the choice has an ID, update the existing record
                $choice = $question->choices()->find($choiceData['id']);
                if ($choice) {
                    $choice->update($choiceData);
                }
            }
        }
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
        return $this->sendSuccess([$question], 'question create successfully');
    }

    private function saveChoice($choices)
    {
        Choice::insert($choices);
    }
}
