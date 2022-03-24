<?php
namespace App\Http\Controllers;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use App\Http\Resources\InquiryResource;
use App\Http\Resources\InquiryCollection;
use App\Models\User;


class InquiryController extends Controller
{
    public function index(){
        $inquiries =InquiryResource::collection(Inquiry::where('status','publish')->orderBy('created_at', 'desc')->paginate(20));
        return $inquiries;        
    }

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

        $inquiry->update(['anchor' => $request->input('anchor')]); 
        $inquiry->update(['kinds' => $request->input('kinds')]);
        $inquiry->update(['phoneNumber' => $request->input('phoneNumber')]);
        $inquiry->update(['question' => $request->input('question')]);
        $inquiry->update(['questioner' => $request->input('questioner')]);
        //$inquiry->update(['questioner' => $request->input('questioner')]);
        $inquiry->update(['remote' => $request->input('remote')]);
        $inquiry->update(['satisfaction' => $request->input('satisfaction')]);
        $inquiry->update(['serial' => $request->input('serial')]);
        $inquiry->update(['type' => $request->input('type')]);
        
        $inquiry->update(['updated_at' => date("Y/m/d H:i:s")]);
        //投稿者IDはアップデートしない

        //日時の情報が渡されてきたときだけcreated_atを更新
        if($request->input('dateAndTime')){
            $inquiry->update(['created_at' =>$request->input('dateAndTime') ]);
        }
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