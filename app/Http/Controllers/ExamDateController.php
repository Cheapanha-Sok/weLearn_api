<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Models\ExamDate;
use Exception;
use Illuminate\Http\Request;

class ExamDateController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(ExamDate::get(), "fetch all exam date");
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => ["required", "string"],
            ]);
            $examDate = new ExamDate();
            $examDate->name = $request->input('name');
            $examDate->save();
        } catch (Exception $e) {
            return $this->sendError($e, "something wihen wrong", 500);
        }

    }
    public function show(int $id)
    {
        try {
            return $this->sendResponse(ExamDate::findOrFail($id), "get 1 exam date");
        } catch (Exception $e) {
            return $this->sendError($e, "somthing when wrong", 500);
        }
    }
    public function destroy(int $id)
    {
        try {
            $examDate = ExamDate::findOrFail($id);
            $examDate->delete();
            return $this->sendResponse(["id", $id], "remove exam date successful");
        } catch (Exception $e) {
            return $this->sendError($e, "somthing when wrong", 500);
        }

    }
    public function edit(int $id, Request $request)
    {
        try {
            $examDate = ExamDate::findOrFail($id);
            if ($request->input('name') != null) {
                $examDate->exam_date = $request->input('name');
                $examDate->save();
                return $this->sendResponse(["id", $id], "edit exam date successful");
            }
        } catch (Exception $e) {
            return $this->sendError($e, "somthing when wrong", 500);
        }

    }
}
