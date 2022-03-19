<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;

use App\Http\Resources\TypeResource;
use App\Http\Resources\TypeCollection;

use DB; 
use Carbon\Carbon;

class TypeController extends Controller
{
    public function create(Type $types,Request $request) {
        
        // $type->name =$request->input('name');
        // $type->save();

        $now = Carbon::now()->format('Y-m-d H:i:s.v');

        $duplicateCheck =  DB::table('types')->where('name',$request->input('name'))->exists();

        if($duplicateCheck){
            //重複あり
            return $duplicateCheck;//true
        }else{
            //重複なし
            $types->name=$request->input('name');
            $types->created_at=$now;
            $types->save();
            return $duplicateCheck;//false
        }
    
        
        
        // $result = DB::table('types')->updateOrInsert([
        //     'name'=>$request->input('name'),
        // ],
        // [
        //     'name'=>$request->input('name'),
        //     'created_at'=>$now
        // ]);
        return $result;
    }
    public function show(Request $request,Type $type) {
        return TypeResource::collection(Type::all());
    }
    public function delete(Request $request,Type $type) {
        return  $type->Where('id', $request->id)->delete();
    }
    public function update(Request $request,Type $type) {
        $type = $type::where('id',$request->input('id'));
        return $type->update(['name' => $request->input('name')]);

    }           
}