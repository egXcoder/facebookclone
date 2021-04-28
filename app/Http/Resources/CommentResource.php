<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'created_at'=>$this->created_at->diffForHumans(),
            'shown'=>$this->created_at->isLastHour(),
            'user'=> new UserResource($this->user),
            'comments'=>self::collection($this->comments),
            'likes'=> LikeResource::collection($this->likes)
        ];
    }
}
