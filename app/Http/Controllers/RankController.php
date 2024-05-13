<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\UserRankRequest;
use App\Http\Resources\RankResource;
use App\Models\Rank;
use Illuminate\Database\Eloquent\Builder;

class RankController extends BaseController
{
    public function store(UserRankRequest $request)
    {
        $request->validated();
        $user = Auth()->user();

        // Attempt to find the existing UserRank record for the user.
        $existingRank = Rank::where('user_id', $user->id)->first();

        if ($existingRank) {
            // If the record exists, update it.
            $existingRank->point += $request->input('point');
            $existingRank->update(); // Save the changes.
        } else {
            // If the record doesn't exist, create a new one.
            Rank::create([
                'point' => $request->input('point')
            ]);
        }

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
