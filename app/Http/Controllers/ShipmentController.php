<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShipmentResource;
use App\Models\Shipment;
use App\Http\Resources\ScheduleResource;
use App\Http\Requests\StoreShipmentRequest;
use App\Http\Requests\UpdateShipmentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Shipment::query();

        $sortField = request("sort_field", 'created_at');
        $sortDirection = request("sort_direction", "desc");

        if (request("name")) {
            $query->where("name", "like", "%" . request("name") . "%");
        }
        if (request("status")) {
            $query->where("status", request("status"));
        }

        $shipments = $query->orderBy($sortField, $sortDirection)
            ->paginate(10)
            ->onEachSide(1);

        return inertia("Shipment/Index", [
            "shipments" => ShipmentResource::collection($shipments),
            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia("Shipment/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShipmentRequest $request)
    {
        $data = $request->validated();
        /** @var $image \Illuminate\Http\UploadedFile */
        $image = $data['image'] ?? null;
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        if ($image) {
            $data['image_path'] = $image->store('shipment/' . Str::random(), 'public');
        }
        Shipment::create($data);

        return to_route('shipment.index')
            ->with('success', 'Shipment was created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        $query = $shipment->schedules();

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
        return inertia('Shipment/Show', [
            'shipment' => new ShipmentResource($shipment),
            "schedules" => ScheduleResource::collection($schedules),
            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment)
    {
        return inertia('Shipment/Edit', [
            'shipment' => new ShipmentResource($shipment),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShipmentRequest $request, Shipment $shipment)
    {
        $data = $request->validated();
        $image = $data['image'] ?? null;
        $data['updated_by'] = Auth::id();
        if ($image) {
            if ($shipment->image_path) {
                Storage::disk('public')->deleteDirectory(dirname($shipment->image_path));
            }
            $data['image_path'] = $image->store('shipment/' . Str::random(), 'public');
        }
        $shipment->update($data);

        return to_route('shipment.index')
            ->with('success', "Shipment \"$shipment->name\" was updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        $name = $shipment->name;
        $shipment->delete();
        if ($shipment->image_path) {
            Storage::disk('public')->deleteDirectory(dirname($shipment->image_path));
        }
        return to_route('shipment.index')
            ->with('success', "Shipment \"$name\" was deleted");
    }
}
