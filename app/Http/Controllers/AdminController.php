<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;

class AdminController extends Controller
{
    public function voters($id)
    {
        require_once base_path('core/ip_handler.php');
        
        $poll = Poll::findOrFail($id);
        $voters = getVotedIps($id);
        
        return response()->json([
            'poll' => $poll,
            'voters' => $voters
        ]);
    }

    public function release(Request $request)
    {
        $request->validate([
            'poll_id' => 'required|integer',
            'ip' => 'required|string',
        ]);

        require_once base_path('core/ip_handler.php');
        
        $result = releaseIp($request->poll_id, $request->ip);
        
        return response()->json([
            'success' => $result,
            'message' => $result ? 'IP released successfully.' : 'Failed to release IP.'
        ]);
    }

    public function history($pollId, $ip)
    {
        require_once base_path('core/ip_handler.php');
        
        $history = getVoteHistory($pollId, urldecode($ip));
        
        return response()->json($history);
    }
}
