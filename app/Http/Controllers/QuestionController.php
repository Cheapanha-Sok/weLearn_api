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
    private function getUserIdentity()
    {
        return Auth::user();

    }
    public function show($categoryId, $levelId)
    {
        $user = $this->getUserIdentity();
        $completedQuestionIds = $this->getUserCompleteQuestion();
        $questions = Question::where('isGraduate', $user->isGraduate)
            ->with('choices')
            ->whereHas('level', function (Builder $query) use ($levelId) {
                $query->where('id', $levelId);
            })
            ->whereHas('category', function (Builder $query) use ($categoryId) {
                $query->where('id', $categoryId);
            })
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
