<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'distance',
        'duration',
        'start_time',
        'polyline',
        'steps',
        'calori',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'polyline' => 'array', // Polyline disimpan dalam bentuk array JSON
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function boot()
    {
        parent::boot();

        static::saved(function ($history) {
            self::updateUserTotals($history->user_id);
        });

        static::deleted(function ($history) {
            self::updateUserTotals($history->user_id);
        });
    }

    protected static function updateUserTotals($userId)
    {
        $totals = self::where('user_id', $userId)
            ->selectRaw('SUM(distance) as total_distance, SUM(duration) as total_duration, SUM(steps) as total_steps, SUM(calori) as total_calori')
            ->first();

        $user = User::find($userId);
        if ($user) {
            $user->update([
                'total_distance' => $totals->total_distance ?? 0,
                'total_duration' => $totals->total_duration ?? 0,
                'total_steps' => $totals->total_steps ?? 0,
                'total_calori' => $totals->total_calori ?? 0,
            ]);
        }
    }

}

