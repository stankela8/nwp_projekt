<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'platform',
        'genre',
        'rank',
        'icon_url',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function playSessions()
    {
        return $this->hasMany(PlaySession::class);
    }

    public function activeSession()
{
    return $this->hasOne(ActiveSession::class);
}

public function goals()
{
    return $this->hasMany(Goal::class);
}

public function gameHoursGoal()
{
    return $this->hasOne(Goal::class)->where('type', 'game_hours');
}

public function rankGoal()
{
    return $this->hasOne(Goal::class)->where('type', 'rank');
}


}

