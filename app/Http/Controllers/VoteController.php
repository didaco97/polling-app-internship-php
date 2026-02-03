<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'poll_id' => 'required|integer',
            'option_id' => 'required|integer',
        ]);

        require_once base_path('core/vote_logic.php');
        require_once base_path('core/ip_handler.php');
        
        $pid = $request->poll_id;
        $oid = $request->option_id;
        $ip = getIp();
        
        if (hasVoted($pid, $ip)) {
            return response()->json([
                'success' => false, 
                'message' => 'You have already voted on this poll from this IP address.'
            ]);
        }
        
        $result = recordVote($pid, $oid, $ip);
        
        return response()->json([
            'success' => $result,
            'message' => $result ? 'Vote recorded successfully!' : 'Failed to record vote.'
        ]);
    }

    public function results($id)
    {
        require_once base_path('core/vote_logic.php');
        return response()->json(getPollResults($id));
    }
}
