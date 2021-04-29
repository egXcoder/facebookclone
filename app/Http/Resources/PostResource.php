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
            'theme'=>ThemeResource::make($this->theme),
            'gif_id'=>$this->gif_id,
            'doingable_type'=>$this->doingable_type,
            'doingable_id'=>$this->doingable_id,
            'created_at'=>$this->created_at,
            'isLiked'=>$this->isLikedByLoggedUser($this->likes),
            'user'=> new UserResource($this->user),
            'likes'=>LikeResource::collection($this->likes),
            'comments'=> CommentResource::collection($this->comments)
        ];
    }

    protected function isLikedByLoggedUser($likes)
    {
        return $likes->first(function ($like) {
            return $like->user_id == auth('api')->user()->id;
        })->type ?? '';
    }
}
