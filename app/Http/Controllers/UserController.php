<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Response\BaseController;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function index()
    {
        return User::all();
    }
    public function update($userId, Request $request)
    {
        $user = User::findOrFail($userId);
        if ($request->input('name') !== null && $user->name !== $request->input('name')) {
            $user->name = $request->input('name');
        }
        if ($request->input('isGraduate') !== null && $user->name !== $request->input('isGraduate')) {
            $user->isGraduate = $request->input('isGraduate');
        }
        $user->update();
        return $this->sendMessage("Update user with id $userId success");
    }
}
