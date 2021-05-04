<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class MessageController extends Controller
{
    public function fetch(User $chatWith)
    {
        $paginator = Message::where(function ($builder) use ($chatWith) {
            return $builder->where('sender_id', auth('api')->user()->id)
                            ->where('receiver_id', $chatWith->id);
        })->orWhere(function ($builder) use ($chatWith) {
            return $builder->where('sender_id', $chatWith->id)
                            ->where('receiver_id', auth('api')->user()->id);
        })
                    ->orderBy('created_at', 'Desc')
                    ->paginate();

        return MessageResource::collection($this->categorizeByCreatedDate($paginator));
    }

    protected function categorizeByCreatedDate(LengthAwarePaginator $paginator)
    {
        $last_created_at = 0;
        $new_collection = collect();
        foreach ($paginator->getCollection()->reverse() as $message) {
            $message->show_date = $message->created_at->diffInMinutes($last_created_at)<=10
                    ? null
                    : ($message->created_at->isSameDay($last_created_at)
                        ? $message->created_at->format('h:i A')
                        : $message->created_at->format('M t, Y, h:i A'));

            $new_collection->push($message);
            $last_created_at = $message->created_at;
        }
        return $paginator->setCollection($new_collection);
    }

    public function store(User $chatWith)
    {
        request()->validate([
            'text'=>'required'
        ]);

        $message = Message::create([
            'sender_id'=>auth()->user()->id,
            'receiver_id'=>$chatWith->id,
            'text'=>request('text')
        ]);

        broadcast(new MessageSent(auth('api')->user(), $chatWith, MessageResource::make(($message))));

        return MessageResource::make($message)->additional(['success'=>'Message is sent successfuly']);
    }
}
