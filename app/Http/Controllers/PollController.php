<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index(Request $request)
    {
        $polls = Poll::active()->get();
        
        if ($request->ajax()) {
            return response()->json($polls);
        }
        
        return view('polls.index', compact('polls'));
    }

    public function show(Request $request, $id)
    {
        $poll = Poll::with('options')->findOrFail($id);
        
        // Check if user already voted
        require_once base_path('core/vote_logic.php');
        require_once base_path('core/ip_handler.php');
        
        $ip = getIp();
        $hasVoted = hasVoted($id, $ip);
        $results = getPollResults($id);
        
        if ($request->ajax()) {
            return response()->json([
                'poll' => $poll,
                'hasVoted' => $hasVoted,
                'results' => $results
            ]);
        }
        
        return view('polls.show', compact('poll', 'hasVoted', 'results'));
    }
}
