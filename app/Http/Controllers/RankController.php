<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\RankRequest;
use App\Http\Resources\RankResource;
use App\Models\Rank;
use App\Models\UserQuestion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RankController extends BaseController
{
    public function store(RankRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $existingRank = Rank::where('user_id', $user->id)->first();

        if ($existingRank) {
            $existingRank->point += $validated['point'];
            $existingRank->update();
        } else {
            Rank::create([
                'user_id' => $user->id,
                'point' => $validated['point']
            ]);
        }
        $questions = $validated['questions'];
        $this->saveCompleteQuestion($questions, $user->id);
        return $this->sendSuccess([], "create successful");

    }

    public function saveCompleteQuestion($questions, $userId)
    {
        $completedQuestions = [];
        foreach ($questions as $questionId) {
            $completedQuestions[] = [
                'user_id' => $userId,
                'question_id' => $questionId
            ];
        }
        UserQuestion::insert($completedQuestions);
    }

    public function show($isGraduate)
    {
        $ranks = Rank::orderByDesc('point')
            ->whereHas('user', function (Builder $query) use ($isGraduate) {
                $query->where('isGraduate', $isGraduate);
            })->limit(10)
            ->get();
        return $this->sendSuccess(RankResource::collection($ranks), "fetch user rank");
    }

}
