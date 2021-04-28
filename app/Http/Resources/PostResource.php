<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'audience_type'=>$this->audience_type,
            'text'=>$this->text,
            'theme_id'=>$this->theme_id,
            'gif_id'=>$this->gif_id,
            'doingable_type'=>$this->doingable_type,
            'doingable_id'=>$this->doingable_id,
            'created_at'=>$this->created_at,
            'user'=> new UserResource($this->user),
            'likes'=>LikeResource::collection($this->likes),
            'comments'=> CommentResource::collection($this->whenLoaded($this->comments))
        ];
    }
}
