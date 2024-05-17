<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\SubjectRequest;
use App\Models\Category;
use App\Models\ExamDate;
use App\Models\Pdf;
use App\Models\Subject;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends BaseController
{
    public function show(int $examDateId, int $categoryId)
    {

        $subject = Subject::with('category')->whereHas('category' , function(Builder $query) use($categoryId){
            $query->where('id', $categoryId);
        })->with('examdate')->whereHas('examdate' , function(Builder $query) use($examDateId){
            $query->where('id' , $examDateId);
        })->get();

        return $this->sendSuccess($subject , "fetch object subject");
    }

    public function showByType(int $typeId)
    {
        $pdfs = DB::table('pdfs')
            ->join('categories', 'pdfs.category_id', '=', 'categories.id')
            ->join('category_type', 'categories.id', '=', 'category_type.category_id')
            ->join('types', 'category_type.type_id', '=', 'types.id')
            ->join('exam_dates', 'pdfs.exam_date_id', '=', 'exam_dates.id')
            ->select('pdfs.id', 'pdfs.pdfUrl', 'categories.name as categoryName', 'types.name as typeName', 'exam_dates.name as examDate')
            ->where('types.id', $typeId)
            ->get();

        return response()->json($pdfs);
    }

    public function index()
    {
        $pdfs = DB::table('pdfs')
            ->join('categories', 'pdfs.category_id', '=', 'categories.id')
            ->join('category_type', 'categories.id', '=', 'category_type.category_id')
            ->join('types', 'category_type.type_id', '=', 'types.id')
            ->join('exam_dates', 'pdfs.exam_date_id', '=', 'exam_dates.id')
            ->select('pdfs.id', 'pdfs.pdfUrl', 'categories.name as categoryName', 'types.name as typeName', 'exam_dates.name as examDate')
            ->get();

        return response()->json($pdfs);
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

    public function destroy(int $id)
    {
        $pdf = Subject::find($id);
        if ($pdf) {
            $url = $pdf->pdfUrl;
            $parts = explode('/', $url);
            $publicId = end($parts);
            $publicId = pathinfo($publicId, PATHINFO_FILENAME);
            $res = Cloudinary::destroy("pdf/$publicId");
            if ($res['result'] == 'ok') {
                $pdf->delete();
                return response()->json(['message' => "PDF with id $id has been removed successfully"], 200);
            } else
                return response()->json(['error' => $res, $publicId], 500);
        } else
            return response()->json(['message' => "pdf with id $id not found"], 404);

    }

    public function edit(Request $request)
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
