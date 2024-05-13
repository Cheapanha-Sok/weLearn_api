<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Models\Category;
use App\Models\Type;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{

    public function index()
    {
        $categories = Category::get();
        $uniqueCategories = [];

        foreach ($categories as $category) {
            if (!in_array($category->name, array_column($uniqueCategories, 'name'))) {
                $uniqueCategories[] = $category;
            }
        }

        return $this->sendResponse($uniqueCategories, "Fetch all unique categories by name");
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'lowercase'],
                'typeId' => ['required'],
            ]);
            $category = Category::create(['name' => $request->input('name')]);

            $type = Type::findOrFail($request->input('typeId'));
            $category->types()->attach($type);

            return $this->sendResponse($category, "create category successfully");
        } catch (Exception $e) {
            return $this->sendError($e, "Something went wrong");
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            if ($request->input('name') != "") {
                $category->name = $request->input("name");
                $category->update();
                return $this->sendResponse(['id', $id], 'category update successfully');
            }
        } catch (Exception $e) {
            return $this->sendError($e, "Something when wrong");
        }


    }
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return $this->sendResponse(['id', $id], 'category delete successfully');
        } catch (Exception $e) {
            return $this->sendError($e, "Something when wrong");
        }

    }
}