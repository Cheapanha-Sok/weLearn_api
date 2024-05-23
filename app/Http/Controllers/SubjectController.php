<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\SubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Category;
use App\Models\ExamDate;
use App\Models\Subject;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SubjectController extends BaseController
{
    public function showPdf(int $examDateId, int $categoryId)
    {
        $subject = Subject::with(['category', 'examdate'])
            ->where('category_id', $categoryId)
            ->where('exam_date_id', $examDateId)
            ->first();
        if ($subject === null) {
            return $this->sendError("subject with examdate id $examDateId and category id $categoryId not found",[], 404);
        } else {
            return $this->sendSuccess(new SubjectResource($subject), "fetch subject object");
        }
    }


    public function show(int $typeId, int $examDateId)
    {
        $subjects = Subject::with(['category', 'examdate'])->
            where('exam_date_id', $examDateId)->
            whereHas('category.types', function (Builder $query) use ($typeId) {
                $query->where('types.id', $typeId);
            })
            ->get();
        return $this->sendSuccess(SubjectResource::collection($subjects), "fetch subeject list");
    }

    public function store(SubjectRequest $request)
    {
        $validated = $request->validated();
        $response = cloudinary()->upload($request->file('file')->getRealPath(), [
            'folder' => "pdf"
        ])->getSecurePath();
        $pdf = Subject::create([
            'category_id' => $validated['category_id'],
            'exam_date_id' => $validated['exam_date_id'],
            'pdfUrl' => $response
        ]);

        return $this->sendSuccess([$pdf], "create subject successful");
    }

    public function destroy(Subject $subject)
    {
        $url = $subject->pdfUrl;
        $parts = explode('/', $url);
        $publicId = end($parts);
        $publicId = pathinfo($publicId, PATHINFO_FILENAME);
        $res = Cloudinary::destroy("pdf/$publicId");
        if ($res['result'] == 'ok') {
            $subject->delete();
            return $this->sendSuccess([], "remove subject success");
        } else
            return $this->sendError("Something when wrong during remove pdf from cloud");

    }

    public function update(Request $request)
    {
        try {
            $pdf = Subject::find($request->input('subjectId'));
            if ($pdf) {
                $categoryId = $request->input('categoryId');
                $examDateId = $request->input('examDateId');
                $file = $request->input('file');
                if ($categoryId !== null) {
                    $category = Category::findOr($categoryId);
                    $pdf->category_id = $category->id;
                }
                if ($examDateId !== null) {
                    $examDate = ExamDate::find($examDateId);
                    $pdf->exam_date_id = $examDate->id;
                }
                if ($file !== null) {
                    $url = $pdf->pdfUrl;
                    $parts = explode('/', $url);
                    $publicId = end($parts);
                    $publicId = pathinfo($publicId, PATHINFO_FILENAME);
                    $res = Cloudinary::destroy("pdf/$publicId");
                    if ($res['result'] == 'ok') {
                        $response = cloudinary()->upload($request->file('file')->getRealPath(), [
                            'folder' => "pdf"
                        ])->getSecurePath();
                        $pdf->pdfUrl = $response;
                    }
                }
                $pdf->save();
                return response()->json(['message' => "PDF with id $pdf->id updated sucessful"], 200);
            } else
                return response()->json(['message' => "pdf with id $request->input('subjectId') not found"], 404);
        } catch (error) {
            return response()->json(['error' => 'error during update'], 500);
        }
    }
}
