<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteHistory extends Model
{
    protected $table = 'vote_history';
    
    public $timestamps = false;

    protected $fillable = ['poll_id', 'option_id', 'ip_address', 'action', 'voted_at', 'released_at', 'created_at'];

    protected $casts = [
        'voted_at' => 'datetime',
        'released_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function option()
    {
        return $this->belongsTo(PollOption::class, 'option_id');
    }
}
