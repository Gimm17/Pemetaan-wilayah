<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function show(Location $location)
    {
        $location->load(['category','photos']);
        return response()->json(['data' => $location]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request, isUpdate: false);
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        return DB::transaction(function () use ($request, $data) {
            $location = Location::create($data);
            $this->handlePhotos($request, $location);

            activity()->performedOn($location)->causedBy($request->user())
                ->withProperties(['source' => 'manual'])
                ->log('location_created');

            return response()->json(['data' => $location->fresh(['photos'])], 201);
        });
    }

    public function update(Request $request, Location $location)
    {
        $data = $this->validatePayload($request, isUpdate: true);
        $data['updated_by'] = $request->user()->id;

        return DB::transaction(function () use ($request, $location, $data) {
            $location->update($data);
            $this->handlePhotos($request, $location);

            activity()->performedOn($location)->causedBy($request->user())
                ->withProperties(['source' => 'manual'])
                ->log('location_updated');

            return response()->json(['data' => $location->fresh(['photos'])]);
        });
    }

    public function destroy(Request $request, Location $location)
    {
        $location->delete();
        activity()->performedOn($location)->causedBy($request->user())->log('location_deleted');
        return response()->json(['message' => 'deleted']);
    }

    private function validatePayload(Request $request, bool $isUpdate): array
    {
        $id = $request->route('location')?->id;

        return $request->validate([
            'fid' => ['nullable','integer'],
            'shape' => ['nullable','string','max:255'],
            'nama' => ['nullable','string','max:255'],
            'nop' => ['nullable','string','max:255', $isUpdate ? 'unique:locations,nop,' . $id : 'unique:locations,nop'],
            'luas' => ['nullable','numeric'],
            'sertpikat' => ['nullable','string','max:255'],
            'njop' => ['nullable','numeric'],
            'luas_bangu' => ['nullable','numeric'],
            'user_perum' => ['nullable','string','max:255'],
            'latitude' => ['required','numeric','between:-90,90'],
            'longitude' => ['required','numeric','between:-180,180'],
            'category_id' => ['nullable','exists:categories,id'],
            'photos.*' => ['nullable','image','max:5120'],
        ]);
    }

    private function handlePhotos(Request $request, Location $location): void
    {
        if (!$request->hasFile('photos')) return;

        foreach ($request->file('photos', []) as $file) {
            if (!$file) continue;
            $path = $file->store('location_photos', 'public');
            $location->photos()->create(['path' => $path]);
        }
    }
}
