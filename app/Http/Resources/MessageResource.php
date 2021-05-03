<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'text'=>$this->text,
            'sender_id'=>$this->sender_id,
            'receiver_id'=>$this->receiver_id,
            'show_date'=>$this->show_date,
        ];
    }
}
