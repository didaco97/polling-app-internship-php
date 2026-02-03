<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = ['question', 'status'];

    public function options()
    {
        return $this->hasMany(PollOption::class)->orderBy('display_order');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
