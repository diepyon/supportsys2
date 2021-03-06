<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;
use App\Http\Resources\InquiryCollection;
use App\Models\Inquiry;
use App\Models\Operator;

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
        $operator = new Operator;

        return [
            'id'=>$this->id,
            'created_at'=> $this->created_at->isoFormat('YYYY/MM/DD(ddd) HH:mm'),
            'updated_at'=> $this->updated_at->isoFormat('YYYY/MM/DD(ddd) HH:mm'),
            'date'=> $this->created_at->isoFormat('YYYY-MM-DD'),
            'time'=> $this->created_at->isoFormat('HH:mm'),
            'dealer'=> $this->dealer,
            'questioner'=> $this->questioner,
            'phoneNumber'=> $this->phoneNumber,
            'phoneNumberWithoutHyphen'=>str_replace('-','', $this->phoneNumber),
            'customer'=> $this->customer,
            'kinds'=> $this->kinds,
            'question'=> $this->question,
            'remote'=> $this->remote,
            'authorizer'=> $this->authorizer,
            'satisfaction'=> $this->satisfaction,
            'operator_id'=> $this->operator_id,
            'operatorName'=>$operator->where('id',$this->operator_id)->first()->name,
            'inquiry_id'=> $this->inquiry_id,
            'answer' => $this->answer,
            'serial' => $this->serial,
            'type' => $this->type,
            'anchor' => $this->anchor,

            //リレー形式で関連記事を表示する部分
            //存在しない記事を手動で引き継い偽もとにするとページ全体がばぐる
            'previewAnchor'=> $inquiry->anchorRelation($this->id),
        ];
    }
}