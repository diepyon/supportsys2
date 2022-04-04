<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;
use App\Http\Resources\CustomerResource;use App\Http\Resources\CustomerCollection;
use DB; 
use Carbon\Carbon;


class CustomerController extends Controller
{
    public function create(Customer $customer,Request $request) {
        //return $request->input('shipdate');
        $customer->type=$request->input('type');
        $customer->serial=$request->input('serial');
        $customer->mac=$request->input('mac');
        $customer->shipdate=$request->input('shipdate');
        $customer->lisence_type=$request->input('lisence_type');
        //$customer->lisence_start=$request->input('lisence_start');
        $customer->lisence_stop=$request->input('lisence_stop');
        $customer->remoteInfo=$request->input('remoteInfo');
        $customer->dealer=$request->input('dealer');
        $customer->customerName=$request->input('customerName');
        $customer->furigana=$request->input('furigana');
        $customer->phoneNumber=$request->input('phoneNumber');
        //$customer->email=$request->input('');
        $customer->address=$request->input('address');
        //$customer->NumberOfClient=$request->input('');
        $customer->detail_type=$request->input('detail_type');
        $customer->remarks=$request->input('remarks');
        
        // $customer->remarks;
        // $customer->other01;
        // $customer->other02;
        // $customer->other03;
        // $customer->other04;
        // $customer->other05;
        // $customer->other06;
        // $customer->other07;
        // $customer->other08;
        // $customer->other09;
        // $customer->other10;
    
        $customer->save();
        return $request;
    }
    public function show(Customer $customer){
        return CustomerResource::collection(Customer::all());
    }
    public function update(Customer $customer,Request $request){
        $customer = $customer::where('id',$request->input('id'));
        //dd($request->input('id'));



        $hoges = array('type','serial','mac','shipdate');
        //ただしフォームに存在しないカラムを更新しようとすると空になっちゃうかも。
        
        // forearch($hoges as $hoge){
        //     $customer->update([$hoge => $request->input($hoge)]);
        // }

        $customer->update(['type' => $request->input('type')]);
        $customer->update(['serial' => $request->input('serial')]);
        $customer->update(['mac' => $request->input('mac')]);
        $customer->update(['shipdate' => $request->input('shipdate')]);
        $customer->update(['lisence_type' => $request->input('lisence_type')]);
        $customer->update(['lisence_stop' => $request->input('lisence_stop')]);
        $customer->update(['remoteInfo' => $request->input('remoteInfo')]);
        $customer->update(['dealer' => $request->input('dealer')]);
        $customer->update(['customerName' => $request->input('customerName')]);
        $customer->update(['furigana' => $request->input('furigana')]);
        $customer->update(['phoneNumber' => $request->input('phoneNumber')]);
        $customer->update(['address' => $request->input('address')]);
        $customer->update(['detail_type' => $request->input('detail_type')]);  
        $customer->update(['remarks' => $request->input('remarks')]);  

        
    }
}