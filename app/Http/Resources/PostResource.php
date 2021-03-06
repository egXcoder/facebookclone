<?php

namespace App\Http\Resources;

use App\Models\PostActivity;
use App\Models\PostFeeling;
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
            'doingable_type'=>$this->doingable_type,
            'doingable_id'=>$this->doingable_id,
            'created_at'=>$this->created_at,
            'isLiked'=>$this->isLikedByLoggedUser($this->likes),
            'theme'=>ThemeResource::make($this->theme),
            'feeling'=> ($this->doingable instanceof PostFeeling) ? $this->doingable : null,
            'activity'=>($this->doingable instanceof PostActivity) ? $this->doingable : null,
            'tagged'=> UserResource::collection($this->tagged),
            'user'=> new UserResource($this->user),
            'likes'=>LikeResource::collection($this->likes),
            'gif'=>GifResource::make($this->gif),
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
