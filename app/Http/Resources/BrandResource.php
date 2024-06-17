<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'display_name'=>$this->display_name,
            'products' => ProductImageResource::collection($this->whenLoaded('products',function(){
                return $this->products->load('images');
            }))
        ];
    }
}
