<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlaySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'played_at',
        'duration_minutes',
        'mode',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
