<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{
    public function create(Request $reques,Type $type) {
        $type->name =$request->input('name');
    }
}
