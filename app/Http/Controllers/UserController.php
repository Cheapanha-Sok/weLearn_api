<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\UserRequest;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function index()
    {
        return User::get();
    }

    public function update(UserRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        if (isset($validated['is_graduate']) && $validated['is_graduate'] !== $user->is_graduate) {
            $this->resetPoint($user->id);
        }
        $user->update($validated);
        return $this->sendSuccess($user, "Updated user successfully");
    }

    private function resetPoint($userId)
    {
        // Find the rank records by user_id
        $ranks = Rank::where('user_id', $userId)->get();
        if ($ranks) {
            foreach ($ranks as $rank) {
                $rank->point = 0;
                $rank->save();
            }
        }
    }

}
