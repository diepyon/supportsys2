<?php

namespace App\Http\Controllers;
use App\Models\Inquiry;
use Illuminate\Http\Request;

use App\Http\Resources\InquiryResource;
use App\Http\Resources\InquiryCollection;

use App\Models\User;

class InquiryController extends Controller
{
    public function create(Request $request ,Inquiry $inquiry) 
    {
        $inquiry->answer =$request->input('answer');
        if($request->input('remote') !='なし'){//remoteがなしなら承認者は登録しない
            $inquiry->authorizer=$request->input('authorizer');
        }
        $inquiry->customer=$request->input('customer');
        $inquiry->dealer=$request->input('dealer');
        $inquiry->kinds=$request->input('kinds');
        $inquiry->phoneNumber=$request->input('phoneNumber');//ハイフン以外の文字列があったら取り除きたい
        $inquiry->question=$request->input('question');
        $inquiry->questioner=$request->input('questioner');
        $inquiry->remote=$request->input('remote');
        $inquiry->satisfaction=$request->input('satisfaction');
        $inquiry->type=$request->input('type');
        $inquiry->operator_id=$request->input('operator_id');
        $inquiry->inquiry_id=substr(bin2hex(random_bytes(8)), 0, 8);
        $inquiry->save();
    }  
    public function show(){
        return InquiryResource::collection(Inquiry::where('status','publish')->get());//公開中の記事のみ表示
    }

    public function single($id){//url上の数値を取得
        $inquiry = Inquiry::find($id);//受け取った数値と一致するIDのレコードを取得
        return new InquiryResource($inquiry);
    }

    public function edit(Request $request ,Inquiry $inquiry){
        //投稿者は別の人に変わらない
        //updateは別途記載
        //引き継ぎ元IDフォームを新たに出現させる？
    }
   
    public function delete(Request $request,Inquiry $inquiry){
        $inquiry = $inquiry::where('id',$request->input('id'));
        $inquiry->update(['status' => 'delete']);
    }
    public function reborn(Request $request,Inquiry $inquiry){
        $inquiry = $inquiry::where('id',$request->input('id'));
        $inquiry->update(['status' => 'publish']);
    }      
}