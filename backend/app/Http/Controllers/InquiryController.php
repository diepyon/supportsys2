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


        

        if($request->input('anchor')){
            $inquiry->anchor=$request->input('anchor');//引き継ぎID手入力
        }elseif($request->input('id')){
            $inquiry->anchor=$inquiry::where('id',$request->input('id'))->first()->inquiry_id;
            //引き継ぎID自動登録 送られてきたIDのレコードを参照してinquiry_idを特定して、親の投稿とみなす
        }

        $inquiry->kinds=$request->input('kinds');
        $inquiry->phoneNumber=$request->input('phoneNumber');//ハイフン以外の文字列があったら取り除きたい
        $inquiry->question=$request->input('question');
        $inquiry->questioner=$request->input('questioner');
        $inquiry->remote=$request->input('remote');
        $inquiry->satisfaction=$request->input('satisfaction');
        $inquiry->serial=$request->input('serial');
        $inquiry->type=$request->input('type');
        $inquiry->operator_id=$request->input('operator_id');
        $inquiry->inquiry_id=substr(bin2hex(random_bytes(8)), 0, 8);
        $inquiry->save();
    }
    
    public function inhert(Request $request ,Inquiry $inquiry){
        //もしかしらcreateとう統一でききるか
        $inquiry->answer =$request->input('answer');
        if($request->input('remote') !='なし'){//remoteがなしなら承認者は登録しない
            $inquiry->authorizer=$request->input('authorizer');
        }
        $inquiry->customer=$request->input('customer');
        $inquiry->dealer=$request->input('dealer');

        $inquiry->anchor=$request->input('anchor');//引き継ぎID

        $inquiry->kinds=$request->input('kinds');
        $inquiry->phoneNumber=$request->input('phoneNumber');//ハイフン以外の文字列があったら取り除きたい
        $inquiry->question=$request->input('question');
        $inquiry->questioner=$request->input('questioner');
        $inquiry->remote=$request->input('remote');
        $inquiry->satisfaction=$request->input('satisfaction');
        $inquiry->serial=$request->input('serial');
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
        $inquiry = $inquiry::where('id',$request->input('id'));

        $inquiry->update(['answer' => $request->input('answer')]);
        if($request->input('remote') !='なし'){//remoteがなしなら承認者は登録しない
            $inquiry->update(['authorizer' => $request->input('authorizer')]);
        }else{//入力があってもnullにする
            $inquiry->update(['authorizer' => null]);
        }
        $inquiry->update(['customer' => $request->input('customer')]);
        $inquiry->update(['dealer' => $request->input('dealer')]);

        //入力欄に引き継ぎ元IDフォームを新たに出現させる？
        $inquiry->update(['anchor' => $request->input('anchor')]);
        
        $inquiry->update(['kinds' => $request->input('kinds')]);
        $inquiry->update(['phoneNumber' => $request->input('phoneNumber')]);
        $inquiry->update(['question' => $request->input('question')]);
        $inquiry->update(['questioner' => $request->input('questioner')]);
        $inquiry->update(['questioner' => $request->input('questioner')]);
        $inquiry->update(['remote' => $request->input('remote')]);
        $inquiry->update(['satisfaction' => $request->input('satisfaction')]);
        $inquiry->update(['serial' => $request->input('serial')]);
        $inquiry->update(['type' => $request->input('type')]);
        $inquiry->update(['updated_at' => date("Y/m/d H:i:s")]);
        //投稿者IDはアップデートしない
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