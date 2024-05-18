<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\ScholarshipRequest;
use App\Models\Scholarship;

class ScholarshipController extends BaseController
{
    public function index()
    {
        $scholarships = Scholarship::orderByDesc('post_at')->get();
        return $this->sendSuccess($scholarships, "fetch scolarship list");
    }
    public function store(ScholarshipRequest $request)
    {

        $validated = $request->validated();
        $scholarship = Scholarship::create($validated);
        return $this->sendSuccess([$scholarship], "create scholarship successful");
    }
    
    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();
        return $this->sendSuccess([], "remove scholarship successful");
    }
}
