<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\ExamDateRequest;
use App\Models\ExamDate;

class ExamDateController extends BaseController
{
    public function index()
    {
        return $this->sendSuccess(ExamDate::orderBy('name')->get(), "fetch all exam date");
    }
    public function store(ExamDateRequest $request)
    {
        $validated = $request->validated();
        $examDate = ExamDate::create($validated);
        return $this->sendSuccess([$examDate], "create examdate successful");

    }
    public function show(int $id)
    {
        return $this->sendSuccess(ExamDate::findOrFail($id), "get 1 exam date");
    }
    public function destroy(ExamDate $examDate)
    {
        $examDate->delete();
        return $this->sendSuccess([], "remove exam date successful");
    }
    public function edit(ExamDateRequest $request, ExamDate $examDate)
    {
        $validated = $request->validated();
        $examDate->update($validated);
        return $this->sendSuccess([$examDate], "update examdate successful");

    }
}
