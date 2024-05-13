<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController;
use App\Http\Resources\TypeResource;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class TypeController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(TypeResource::collection(Type::get()), "type list");
    }

    public function show($id)
    {
        $type = Type::with('categories')->find($id);

        if (!$type) {
            return $this->sendError("type with $id not found");
        }

        return $this->sendResponse(new TypeResource($type), "type object");
    }
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|lowercase|unique:types',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Failed validation');
        }
        $type = Type::create([$request]);
        return $this->sendResponse($type, "create type success");
    }

    public function edit(Request $request, $id)
    {
        $type = Type::findOrFail($id);
        if ($request->input('name') != "") {
            $type->name = $request->input("name");
            $updatedType = $type->save();
            return $this->sendResponse($updatedType, "updated type succesful");
        }
    }
    public function destroy($id)
    {
        $type = Type::findOrFail($id);
        $type->delete();
        return $this->sendResponse($id, "remove type succesful");
    }
}
