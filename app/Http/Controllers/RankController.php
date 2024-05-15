<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\UserRankRequest;
use App\Http\Resources\RankResource;
use App\Models\Rank;
use App\Models\UserQuestion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RankController extends BaseController
{
    public function store(UserRankRequest $request)
    {
        $request->validated();
        $user = Auth::user();

        // Attempt to find the existing UserRank record for the user.
        $existingRank = Rank::where('user_id', $user->id)->first();

        if ($existingRank) {
            // If the record exists, update it.
            $existingRank->point += $request->input('point');
            $existingRank->save(); // Save the changes.
        } else {
            // If the record doesn't exist, create a new one.
            Rank::create([
                'user_id' => $user->id, // Ensure the user_id is set
                'point' => $request->input('point')
            ]);
        }
        $questions = $request->input('questions'); // This should be an array of question IDs

        // Prepare data for batch insert using the UserQuestion model
        $completedQuestions = [];
        foreach ($questions as $questionId) {
            $completedQuestions[] = [
                'user_id' => $user->id,
                'question_id' => $questionId
            ];
        }
        UserQuestion::insert($completedQuestions);
        return $this->sendResponse([], "create successful");
    }

    public function show($isGraduate)
    {
        $ranks = Rank::orderByDesc('point')
            ->with('user')->whereHas('user', function (Builder $query) use ($isGraduate) {
                $query->where('isGraduate', $isGraduate);
            })->limit(10)
            ->get();
        return $this->sendResponse(RankResource::collection($ranks), "fetch user rank");
    }
    public function index()
    {
        return $this->sendResponse(Rank::orderBy('point')->paginate(10), "fetch rank list");
    }

}
