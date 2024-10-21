<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalPendingSchedules = Schedule::query()
            ->where('status', 'pending')
            ->count();
        $myPendingSchedules = Schedule::query()
            ->where('status', 'pending')
            ->where('assigned_user_id', $user->id)
            ->count();


        $totalProgressSchedules = Schedule::query()
            ->where('status', 'in_progress')
            ->count();
        $myProgressSchedules = Schedule::query()
            ->where('status', 'in_progress')
            ->where('assigned_user_id', $user->id)
            ->count();


        $totalCompletedSchedules = Schedule::query()
            ->where('status', 'completed')
            ->count();
        $myCompletedSchedules = Schedule::query()
            ->where('status', 'completed')
            ->where('assigned_user_id', $user->id)
            ->count();

        $activeSchedules = Schedule::query()
            ->whereIn('status', ['pending', 'in_progress'])
            ->where('assigned_user_id', $user->id)
            ->limit(10)
            ->get();
        $activeSchedules = ScheduleResource::collection($activeSchedules);
        return inertia(
            'Dashboard',
            compact(
                'totalPendingSchedules',
                'myPendingSchedules',
                'totalProgressSchedules',
                'myProgressSchedules',
                'totalCompletedSchedules',
                'myCompletedSchedules',
                'activeSchedules'
            )
        );
    }
}
