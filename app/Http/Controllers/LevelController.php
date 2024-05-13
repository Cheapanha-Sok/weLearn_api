<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(Level::get(), "fetch level list");
    }
    public function show($id)
    {
        return $this->sendResponse(Level::findOrFail($id), "fetch level object");
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'lowercase', 'unique:levels'],
            'point' => ['required', 'integer']
        ]);

        $level = Level::create([
            'name' => $request->input('name'),
            'point' => $request->input('point')
        ]);

        return $this->sendResponse($level, "create level successful");
    }

    public function edit(Request $request, $id)
    {
        $level = Level::findOrFail($id);
        if ($request->input('name') != "") {
            $level->name = $request->input("name");
            $updatedLevel = $level->save();
            return $this->sendResponse($updatedLevel, "updated level successful");
        }

    }
    public function destroy($id)
    {
        $level = Level::findOrFail($id);
        $level->delete();
        return $this->sendResponse($id, "category delete successfully");
    }
}
