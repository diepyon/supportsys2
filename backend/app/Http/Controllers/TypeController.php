<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;

use App\Http\Resources\TypeResource;
use App\Http\Resources\TypeCollection;

class TypeController extends Controller
{
    public function create(Request $request,Type $type) {
        $type->name =$request->input('name');
        $type->save();
    }
    
    public function show(Request $request,Type $type) {
        return TypeResource::collection(Type::all());

    }
}