<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['poll_id', 'option_id', 'ip_address', 'is_released', 'voted_at', 'released_at'];

    protected $casts = [
        'is_released' => 'boolean',
        'voted_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function option()
    {
        return $this->belongsTo(PollOption::class, 'option_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_released', false);
    }
}
