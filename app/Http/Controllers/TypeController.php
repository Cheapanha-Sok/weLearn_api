<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Requests\TypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\Type;


class TypeController extends BaseController
{
    public function index()
    {
        return $this->sendSuccess(TypeResource::collection(Type::get()), "type list");
    }
    public function show($id)
    {
        $type = Type::with('categories')->findOrFail($id);
        return $this->sendSuccess(new TypeResource($type), "type object");
    }
    public function store(TypeRequest $request)
    {
        $validated = $request->validated();
        $type = Type::create($validated);
        return $this->sendSuccess([$type], "create type success");
    }

    public function edit(TypeRequest $request, Type $type)
    {
        $validated = $request->validated();
        $type->update($validated);
        return $this->sendSuccess([$type], "updated type succesful");
    }
    public function destroy(Type $type)
    {
        $type->delete();
        return $this->sendSuccess([], "remove type succesful");
    }
}
