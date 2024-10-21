<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShipmentResource;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\UserResource;
use App\Models\Shipment;
use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Schedule::query();

        $sortField = request("sort_field", 'created_at');
        $sortDirection = request("sort_direction", "desc");

        if (request("name")) {
            $query->where("name", "like", "%" . request("name") . "%");
        }
        if (request("status")) {
            $query->where("status", request("status"));
        }

        $schedules = $query->orderBy($sortField, $sortDirection)
            ->paginate(10)
            ->onEachSide(1);

        return inertia("Schedule/Index", [
            "schedules" => ScheduleResource::collection($schedules),
            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shipments = Shipment::query()->orderBy('name', 'asc')->get();
        $users = User::query()->orderBy('name', 'asc')->get();

        return inertia("Schedule/Create", [
            'shipments' => ShipmentResource::collection($shipments),
            'users' => UserResource::collection($users),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleRequest $request)
    {
        $data = $request->validated();
        /** @var $image \Illuminate\Http\UploadedFile */
        $image = $data['image'] ?? null;
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        if ($image) {
            $data['image_path'] = $image->store('schedule/' . Str::random(), 'public');
        }
        Schedule::create($data);

        return to_route('schedule.index')
            ->with('success', 'Schedule was created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        return inertia('Schedule/Show', [
            'schedule' => new ScheduleResource($schedule),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $shipments = Shipment::query()->orderBy('name', 'asc')->get();
        $users = User::query()->orderBy('name', 'asc')->get();

        return inertia("Schedule/Edit", [
            'schedule' => new ScheduleResource($schedule),
            'shipments' => ShipmentResource::collection($shipments),
            'users' => UserResource::collection($users),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $data = $request->validated();
        $image = $data['image'] ?? null;
        $data['updated_by'] = Auth::id();
        if ($image) {
            if ($schedule->image_path) {
                Storage::disk('public')->deleteDirectory(dirname($schedule->image_path));
            }
            $data['image_path'] = $image->store('schedule/' . Str::random(), 'public');
        }
        $schedule->update($data);

        return to_route('schedule.index')
            ->with('success', "Schedule \"$schedule->name\" was updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $name = $schedule->name;
        $schedule->delete();
        if ($schedule->image_path) {
            Storage::disk('public')->deleteDirectory(dirname($schedule->image_path));
        }
        return to_route('schedule.index')
            ->with('success', "Schedule \"$name\" was deleted");
    }

    public function mySchedules()
    {
        $user = auth()->user();
        $query = Schedule::query()->where('assigned_user_id', $user->id);

        $sortField = request("sort_field", 'created_at');
        $sortDirection = request("sort_direction", "desc");

        if (request("name")) {
            $query->where("name", "like", "%" . request("name") . "%");
        }
        if (request("status")) {
            $query->where("status", request("status"));
        }

        $schedules = $query->orderBy($sortField, $sortDirection)
            ->paginate(10)
            ->onEachSide(1);

        return inertia("Schedule/Index", [
            "schedules" => ScheduleResource::collection($schedules),
            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }
}
