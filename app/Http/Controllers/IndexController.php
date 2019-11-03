<?php

namespace App\Http\Controllers;

use App\ChatRoom;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function indexRefresh(Request $request)
    {
        $aData = [
          'chat' => ChatRoom::chatRefresh($request),
        ];

        return response()->json([
          'success' => true,
          'data' => $aData
        ]);
    }
}
