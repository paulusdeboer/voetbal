<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'rating', 'previous_rating', 'type', 'user_id'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($player) {
            if ($player->user) {
                $player->user->delete();
            }
        });

        static::restoring(function ($player) {
            if ($player->user) {
                $player->user->restore();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Game::class, 'teams')->withPivot('team');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'rated_player_id');
    }

    public function gamePlayerRatings()
    {
        return $this->hasMany(GamePlayerRating::class);
    }
}
