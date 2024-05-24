<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\UserRequest;
use App\Models\Rank;
use App\Models\User; 

class UserController extends BaseController
{
    public function index()
    {
        return User::get();
    }

    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();
        if (isset($validated['isGraduate']) && $validated['isGraduate'] !== $user->isGraduate) {
            $this->resetPoint($user->id);
        }
        $user->update($validated);

        return $this->sendSuccess($user, "Updated user successfully");
    }


    private function resetPoint($userId)
    {
        // Find the rank record by user_id
        $rank = Rank::where('user_id', $userId)->firstOrFail();
        $rank->point = 0;
        $rank->save();
    }
}
