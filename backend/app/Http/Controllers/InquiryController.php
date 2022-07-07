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
        $inquiry->fill(array_merge($request->all(),
             ['inquiry_id' => substr(bin2hex(random_bytes(8)), 0, 8)],
        ))->save();       

        //下記もonlyを使いたい
        if($request->remote !='なし'){//remoteがなしなら承認者は登録しない
            $inquiry->authorizer=$request->authorizer;
        }
        if($request->anchor){
            $inquiry->anchor=$request->anchor;//引き継ぎID手入力
        }elseif($request->id){
            $inquiry->anchor=$inquiry::where('id',$request->id)->first()->inquiry_id;
            //引き継ぎID自動登録 送られてきたIDのレコードを参照してinquiry_idを特定して、親の投稿とみなす
        }
        $inquiry->save();
    }

    //createと統一できた
    // public function inhert(Request $request ,Inquiry $inquiry){
    //     
    //     //$inquiry->answer =$request->answer;
    //     if($request->remote !='なし'){//remoteがなしなら承認者は登録しない
    //         $inquiry->authorizer=$request->authorizer;
    //     }
    //     $inquiry->customer=$request->customer;
    //     $inquiry->dealer=$request->dealer;
    //     $inquiry->anchor=$request->anchor;//引き継ぎID
    //     $inquiry->kinds=$request->kinds;
    //     $inquiry->phoneNumber=$request->phoneNumber;//ハイフン以外の文字列があったら取り除きたい
    //     $inquiry->question=$request->question;
    //     $inquiry->questioner=$request->questioner;
    //     $inquiry->remote=$request->remote;
    //     $inquiry->satisfaction=$request->satisfaction;
    //     $inquiry->serial=$request->serial;
    //     $inquiry->type=$request->type;
    //     $inquiry->operator_id=$request->operator_id;
    //     $inquiry->inquiry_id=substr(bin2hex(random_bytes(8)), 0, 8);
    //     $inquiry->save();
    // }    

    public function edit(Request $request ,Inquiry $inquiry){
        $inquiry = $inquiry::where('id',$request->id);
        $inquiry->update(['answer' => $request->answer]);
        if($request->remote !='なし'){//remoteがなしなら承認者は登録しない
            $inquiry->update(['authorizer' => $request->authorizer]);
        }else{//入力があってもnullにする
            $inquiry->update(['authorizer' => null]);
        }
        $inquiry->update(['customer' => $request->customer]);
        $inquiry->update(['dealer' => $request->dealer]);
        $inquiry->update(['anchor' => $request->anchor]); 
        $inquiry->update(['kinds' => $request->kinds]);
        $inquiry->update(['phoneNumber' => $request->phoneNumber]);
        $inquiry->update(['question' => $request->question]);
        $inquiry->update(['questioner' => $request->questioner]);
        //$inquiry->update(['questioner' => $request->questioner]);
        $inquiry->update(['remote' => $request->remote]);
        $inquiry->update(['satisfaction' => $request->satisfaction]);
        $inquiry->update(['serial' => $request->serial]);
        $inquiry->update(['type' => $request->type]);
        $inquiry->update(['updated_at' => date("Y/m/d H:i:s")]);
        //投稿者IDはアップデートしない

        //日時の情報が渡されてきたときだけcreated_atを更新
        if($request->dateAndTime){
            $inquiry->update(['created_at' =>$request->dateAndTime ]);
        }
    }
    public function delete(Request $request,Inquiry $inquiry){
        $inquiry = $inquiry::where('id',$request->id);
        $inquiry->update(['status' => 'delete']);
    }
    public function reborn(Request $request,Inquiry $inquiry){
        $inquiry = $inquiry::where('id',$request->id);
        $inquiry->update(['status' => 'publish']);
    }      
}