<?php

namespace App\Http\Controllers;
use App\Models\Inquiry;
use Illuminate\Http\Request;

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
}