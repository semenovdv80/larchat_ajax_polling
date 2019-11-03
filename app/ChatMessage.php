<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['sender_id', 'message', 'receiver_id', 'chat_room_id'];

    /**
     * Chat room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * Message sender
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Message receiver
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    /**
     * List of room messages
     *
     * @param $request
     * @return array
     */
    public static function messages($request)
    {
        $request['user_id'] = auth()->id();

        return self::with('chatRoom:id,name')
          ->with('sender:id,name')
          ->with('receiver:id,name')
          ->filter($request)
          ->orderBy('created_at', 'DESC')
          ->paginate(25);
    }


    /**
     * Messages filter
     *
     * @param $query
     * @param $request
     */
    public function scopeFilter($query, $request)
    {
        $query->when($request->room_id, function ($query) use($request) {
            $query->where('chat_room_id', $request->room_id);
        })->when($request->user_id, function ($query) use($request) {
            $query->participant($request);
        });
    }

    /**
     * Filter by user as conversation participant
     *
     * @param $query
     * @param $request
     */
    public function scopeParticipant($query, $request)
    {
        if (!empty($request->receiver_id)) {
            $query->partner($request);
        } else {
            $query->where('sender_id', $request->user_id)->orWhere('receiver_id', $request->user_id);
        }
    }

    /**
     * Filter by conversation partner
     *
     * @param $query
     * @param $request
     */
    public function scopePartner($query, $request)
    {
        $query->where(function ($query) use ($request) {
            $query->where('sender_id', $request->user_id)->where('receiver_id', $request->receiver_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('receiver_id', $request->user_id)->where('sender_id', $request->receiver_id);
        });
    }
}
