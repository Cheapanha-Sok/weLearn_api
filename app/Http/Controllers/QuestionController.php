<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\QuestionRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Choice;
use App\Models\Level;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;

class QuestionController extends BaseController
{
    public function index()
    {
        $questions = Question::with('choices')->get();
        return $this->sendResponse($questions , "fetch question");
    }

    public function show($categoryId, $levelId)
    {
        $questions = Question::with('choices')
            ->join('levels', 'questions.level_id', '=', 'levels.id')
            ->join('categories', 'questions.category_id', '=', 'categories.id')
            ->select('questions.id', 'questions.name', 'levels.name as level', 'categories.name as category', 'levels.point as point')
            ->where("questions.category_id", $categoryId)
            ->where('questions.level_id', $levelId)
            ->get();
        return $this->sendResponse($questions, "fetch question list");
    }


    public function edit(Request $request, $id)
    {
        $category = Question::find($id);
        if ($category != null) {
            if ($request->input('name') != "") {
                $category->name = $request->input("name");
                $category->save();
                return response()->json(['message' => 'category updated successfully'], 200);
            }
        }
        return response()->json(['message' => "categories with id $id not found"], 404);

    }
    public function destroy($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->delete();
            return $this->sendMessage("question with id $id remove sucessful");
        } catch (Exception $e) {
            return $this->sendError($e, "Something when wrong during delete question");
        }

    }
    public function store(QuestionRequest $request)
    {
        try {
            $request->validated();
            $question = Question::create([
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'level_id' => $request->input('level_id'),
                'isGraduate' => $request->input('isGraduate')
            ]);

            $choicesData = $request->input('choices');

            foreach ($choicesData as $choiceData) {
                $choiceName = $choiceData['name'];
                $isCorrect = $choiceData['is_correct'];

                $this->saveChoice($choiceName, $isCorrect, $question->id);
            }
            return $this->sendMessage('Question created successfully');
        } catch (Exception $e) {
            return $this->sendError($e, 'Something went wrong during create question');
        }
    }

    private function saveChoice($choiceName, $isCorrect, $questionId)
    {
        try {
            Choice::create([
                'name' => $choiceName,
                'is_correct' => $isCorrect,
                'question_id' => $questionId
            ]);
        } catch (Exception $e) {
            return $this->sendError($e, 'Something went wrong during save choice', 500);
        }
    }

}
