<?php

namespace App\Http\Controllers;

use App\Models\SessionLog;
use Illuminate\Http\Request;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;

class SessionLogController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'duration' => 'required|integer'
        ]);

        $sessionLog = SessionLog::create($request->only('user_id', 'duration'));

        return $sessionLog;
    }

    /**
     * Display yearly and monthly overall report.
     *
     * @return \Illuminate\Http\Response
     */
    public function overall(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $result['yearly']['count'] = SessionLog::query()
            ->where('user_id', $request->user_id)
            ->whereYear('created_at', now())
            ->count();

        $result['yearly']['longest_streak'] = SessionLog::query()
            ->where('user_id', $request->user_id)
            ->whereYear('created_at', now())
            ->orderBy('day_of_streak', 'DESC')
            ->first();

        $result['yearly']['longest_streak'] = $result['yearly']['longest_streak'] ? $result['yearly']['longest_streak'] : 0;

        $result['yearly']['total_duration']['seconds'] = SessionLog::query()
            ->where('user_id', $request->user_id)
            ->whereYear('created_at', now())
            ->sum('duration');

        $result['yearly']['total_duration']['formatted'] =
            CarbonInterval::seconds($result['yearly']['total_duration']['seconds'])
            ->cascade()
            ->forHumans(['options' => 0]);

        $result['monthly']['count'] = SessionLog::query()
            ->where('user_id', $request->user_id)
            ->whereMonth('created_at', now())
            ->count();

        $result['monthly']['longest_streak'] = SessionLog::query()
            ->where('user_id', $request->user_id)
            ->whereYear('created_at', now())
            ->orderBy('day_of_streak', 'DESC')
            ->first();

        $result['monthly']['longest_streak'] = $result['monthly']['longest_streak'] ? $result['monthly']['longest_streak'] : 0;

        $result['monthly']['total_duration']['seconds'] = SessionLog::query()
            ->where('user_id', $request->user_id)
            ->whereMonth('created_at', now())
            ->sum('duration');

        $result['monthly']['total_duration']['formatted'] =
            CarbonInterval::seconds($result['monthly']['total_duration']['seconds'])->cascade()->forHumans(['options' => 0]);

        return response()->json($result);
    }

    /**
     * Display session durations of last 7 days.
     *
     * @return \Illuminate\Http\Response
     */
    public function last7DaysDuration(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $date = now()->subDays(6);

        while ($date <= now()->endOfDay()) {
            $result[$date->format('Y-m-d')]['duration'] = (int)SessionLog::query()
                ->where('user_id', $request->user_id)
                ->whereDate('created_at', $date)
                ->sum('duration');
            $result[$date->format('Y-m-d')]['formatted']
                = CarbonInterval::seconds($result[$date->format('Y-m-d')]['duration'])->cascade()->forHumans(['options' => 0]);
            $date->addDays(1);
        }

        return response()->json($result);
    }

    /**
     * Display active days of month.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeDaysOfMonth(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $result = SessionLog::query()
            ->select(DB::raw('DATE(created_at) as date'))
            ->where('user_id', $request->user_id)
            ->whereMonth('created_at', now())
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('date')
            ->toArray();

        return response()->json($result);
    }
}
