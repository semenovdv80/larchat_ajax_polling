<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\ChatRoom;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        return view('chat', [
          'rooms' => ChatRoom::userRooms($request)
        ]);
    }
    /**
     * User's chat rooms
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rooms(Request $request)
    {
        $rooms = ChatRoom::userRooms($request);

        return response()->json([
          'success' => true,
          'data' => $rooms,
        ]);
    }

    /**
     * List of users
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function users(Request $request)
    {
        $users = ChatRoom::roomUsers($request);

        return response()->json([
          'success' => true,
          'data' => $users,
        ]);
    }

    /**
     * List of room messages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function messages(Request $request)
    {
        $messages = ChatMessage::messages($request);

        return response()->json([
          'success' => true,
          'data' => $messages,
        ]);
    }

    /**
     * Send mess
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        $user = $request->user();
        ChatMessage::create([
          'sender_id' => $user->id,
          'message' => $request->get('message'),
          'receiver_id' => $request->get('user_id'),
          'chat_room_id' => $request->get('room_id'),
        ]);

        return response()->json([
          'success' => true,
        ]);
    }
}
