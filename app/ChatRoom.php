<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    const ROOM_ACTIVE  = 1;
    const ROOM_BLOCKED = 2;
    const ROOM_DELETED = 3;

    const USER_ACTIVE  = 1;
    const USER_BLOCKED = 2;

    /**
     * List of chat's messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * List of users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * List of chat's users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function chatUsers()
    {
        return $this->hasMany(User::class);
    }

    /**
     * List of user's rooms
     *
     * @param $request
     * @return mixed
     */
    public static function userRooms($request)
    {
        $user = $request->user();
        return $user->chatRooms()
          ->where('room_status', ChatRoom::ROOM_ACTIVE)
          ->where('user_status', ChatRoom::USER_ACTIVE)
          ->paginate(25);
    }

    /**
     * List of room users
     *
     * @param $request
     * @return mixed
     */
    public static function roomUsers($request)
    {
        $user = $request->user();
        $chatRoom = ChatRoom::find($request->room_id);
        if (!empty($chatRoom)) {
            return $chatRoom->users()
              ->where('user_id','!=', $user->id)
              ->where('user_status', ChatRoom::USER_ACTIVE)
              ->paginate(25);
        }
        return [];
    }

    public static function chatRefresh($request)
    {

    }
}
