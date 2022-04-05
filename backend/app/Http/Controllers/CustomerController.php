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
        $customer->create($request->all());
        return $request;
    }
    public function index(Customer $customer){
        return CustomerResource::collection(Customer::where('status','publish')->orderBy('created_at', 'desc')->paginate(2));
    }
    public function update(Customer $customer,Request $request){
        $customer = Customer::find($request->input('id'));
        $customer->update($request->all());
    }
}