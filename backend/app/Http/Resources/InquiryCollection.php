<?php

namespace App\Http\Resources;

use App\Models\Inquiry;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\InquiryResource;



class InquiryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {//なくても動いた。必要性が分からない。
        return [
            'data' => $this->collection,
        ];
    }
}
