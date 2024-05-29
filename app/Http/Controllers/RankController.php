<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\RankRequest;
use App\Http\Resources\RankResource;
use App\Models\Rank;
use App\Models\UserQuestion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RankController extends BaseController
{
    public function store(RankRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $categoryId = $validated['category_id'];
        $userId = $user->id;

        DB::transaction(function () use ($validated, $userId, $categoryId) {
            $existingRank = Rank::where('user_id', $userId)
                ->where('category_id', $categoryId)
                ->first();

            if ($existingRank) {
                $existingRank->increment('point', $validated['point']);
            } else {
                Rank::create([
                    'point' => $validated['point'],
                    'user_id' => $userId,
                    'category_id' => $categoryId
                ]);
            }

            $questions = $validated['questions'];
            $this->saveCompleteQuestion($questions, $userId);
        });

        return $this->sendSuccess([], "Create successful");
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


    public function show($categoryId, $isGraduate)
    {
        $ranks = Rank::orderByDesc('point')->with('user')
            ->whereHas('user', function (Builder $query) use ($isGraduate) {
                $query->where('is_graduate', $isGraduate);
            })
            ->whereHas('category', function (Builder $query) use ($categoryId) {
                $query->where('id', $categoryId);
            })
            ->limit(10)
            ->get();
        return $this->sendSuccess(RankResource::collection($ranks), "Fetch user rank");
    }

}
