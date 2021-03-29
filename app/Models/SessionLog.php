<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterval;

class SessionLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'duration'];

    protected $appends = ['duration_formatted'];

    public function getDurationFormattedAttribute()
    {
        return CarbonInterval::seconds($this->duration)->cascade()->forHumans();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($log) {
            $previouesDaysLog = self::query()
                ->where('user_id', $log->user_id)
                ->whereDate('created_at', now()->subDay())
                ->latest()
                ->first();

            if ($previouesDaysLog) {
                $log->day_of_streak = $previouesDaysLog->day_of_streak + 1;
                $log->save();
            }
        });
    }
}
