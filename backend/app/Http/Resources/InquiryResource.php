<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;
use App\Http\Resources\InquiryCollection;
use App\Models\Inquiry;

class InquiryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *a
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $inquiry =  new Inquiry;
        return [
            'id'=>$this->id,
            'created_at'=> $this->created_at->isoFormat('YYYY/MM/DD(ddd) HH:mm'),
            'updated_at'=> $this->updated_at->isoFormat('YYYY/MM/DD(ddd) HH:mm'), 
            'dealer'=> $this->dealer,
            'questioner'=> $this->questioner,
            'phoneNumber'=> $this->phoneNumber,
            'customer'=> $this->customer,
            'kinds'=> $this->kinds,
            'question'=> $this->question,
            'remote'=> $this->remote,
            'authorizer'=> $this->authorizer,
            'satisfaction'=> $this->satisfaction,
            'operator_id'=> $this->operator_id,
            'inquiry_id'=> $this->inquiry_id,
            'answer' => $this->answer,
            'serial' => $this->serial,
            'anchor' => $this->anchor,
            'previewAnchor'=> $inquiry->anchorRelation($this->id),//リレー形式で関連記事を表示する部分
        ];
    }
}
