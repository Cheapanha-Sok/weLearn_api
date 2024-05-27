<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;

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
        return $this->sendSuccess($uniqueCategories, "Fetch all unique categories by name");
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        $category = Category::create(['name' => $validated['name']]);
        $category->types()->attach($validated['type_id']);
        return $this->sendSuccess([$category], "create category successfully");
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        $category->update($validated);
        return $this->sendSuccess([$category], "category update successfully");
    }
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->sendSuccess([], 'category delete successfully');
    }
}