<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;
use App\Http\Resources\CustomerCollection;
use App\Models\Customer;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at->isoFormat('YYYY/MM/DD'),
            'updated_at' => $this->updated_at,

            'id' => $this->id,
            'type' => $this->type,
            'serial' => $this->serial,

            'mac' => $this->mac,
            'lisence_type' => $this->lisence_type,
            'dealer' => $this->dealer,
            'remoteInfo' => $this->remoteInfo,
            'customerName' => $this->customerName,
            'phoneNumber' => $this->phoneNumber,
            'email' => $this->email,
            'address' => $this->address,
            'NumberOfClient' => $this->NumberOfClient,
            'remarks' => $this->remarks,
            'detail_type' => $this->detail_type,
            'shipdate' => $this->shipdate,
            'lisence_start' => $this->lisence_start,
            'lisence_stop' => $this->lisence_stop,

            'other01' => $this->other01,
            'other02' => $this->other02,
            'other03' => $this->other03,
            'other04' => $this->other04,
            'other05' => $this->other05,
            'other06' => $this->other06,
            'other07' => $this->other07,
            'other08' => $this->other08,
            'other09' => $this->other09,
            'other10' => $this->other10,

            //'serial' =>  $this->created_at->isoFormat('YYYY/MM/DD'),//createdatがnullのレコードがあるとarchive丸ごと表示できなくなる
        ];
    }
}