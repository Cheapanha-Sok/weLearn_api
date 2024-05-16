<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\LevelRequest;
use App\Models\Level;

class LevelController extends BaseController
{
    public function index()
    {
        return $this->sendSuccess(Level::get(), "fetch level list");
    }
    public function show($id)
    {
        return $this->sendSuccess(Level::findOrFail($id), "fetch level object");
    }
    public function store(LevelRequest $request)
    {
        $validated = $request->validated();
        $level = Level::create($validated);
        return $this->sendSuccess([$level], "create level successful");
    }

    public function edit(LevelRequest $request, Level $level)
    {
        $validated = $request->validated();
        $level->update($validated);
        return $this->sendSuccess([$level], "updated level successful");
    }
    public function destroy(Level $level)
    {
        $level->delete();
        return $this->sendSuccess([], "category delete successfully");
    }
}
