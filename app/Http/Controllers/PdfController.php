<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ExamDate;
use App\Models\Pdf;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    public function show(int $examDateId, int $categoryId)
    {
        $pdf = DB::table('pdfs')
            ->join('categories', 'pdfs.category_id', '=', 'categories.id')
            ->join('exam_dates', 'pdfs.exam_date_id', '=', 'exam_dates.id')
            ->select('pdfs.id', 'pdfs.pdfUrl', 'categories.name as categoryName', 'exam_dates.name as examDate')
            ->where('pdfs.exam_date_id', $examDateId)
            ->where('pdfs.category_id', $categoryId)
            ->first();

        if (!$pdf) {
            return response(['message' => 'PDF not found'], 404);
        }
        return response()->json($pdf);
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


    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => ['required', 'file'],
                'examDateId' => ['required', 'int'],
                'categoryId' => ['required', 'int'],
            ]);
            $category = Category::findOrFail($request->input('categoryId'));
            $examDate = ExamDate::findOrFail($request->input('examDateId'));
            $response = cloudinary()->upload($request->file('file')->getRealPath(), [
                'folder' => "pdf"
            ])->getSecurePath();
            $pdf = new Pdf();
            $pdf->pdfUrl = $response;
            $pdf->exam_date_id = $examDate->id;
            $pdf->category_id = $category->id;
            $pdf->save();

            return response()->json(['message' => 'PDF stored successfully'], 200);
        } catch (error) {
            return response()->json(['error' => 'error during create new bakdoub'], 500);
        }

    }

    public function destroy(int $id)
    {
        $pdf = Pdf::find($id);
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
            $pdf = Pdf::find($request->input('subjectId'));
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
