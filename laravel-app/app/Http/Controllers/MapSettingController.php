<?php

namespace App\Http\Controllers;

use App\Models\MapSetting;
use Illuminate\Http\Request;

class MapSettingController extends Controller
{
    /**
     * Display map settings form
     */
    public function index()
    {
        $mapSettings = MapSetting::first();

        if (!$mapSettings) {
            // Create default settings if none exist
            $mapSettings = new MapSetting([
                'google_maps_api_key' => '',
                'latitude' => 55.7558, // Moscow default
                'longitude' => 37.6176,
                'zoom_level' => 15,
                'company_address' => '',
                'map_style' => 'roadmap',
                'is_active' => true
            ]);
        }

        return view('admin.map-settings.index', compact('mapSettings'));
    }

    /**
     * Update map settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'google_maps_api_key' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'zoom_level' => 'nullable|integer|between:1,20',
            'company_address' => 'nullable|string|max:500',
            'map_style' => 'nullable|in:roadmap,satellite,hybrid,terrain',
            'is_active' => 'nullable|in:on'
        ]);

        $data = $request->only([
            'google_maps_api_key', 'latitude', 'longitude',
            'zoom_level', 'company_address', 'map_style'
        ]);
        $data['is_active'] = $request->has('is_active');

        // Find existing record or create new one
        $mapSettings = MapSetting::first();

        if ($mapSettings) {
            $mapSettings->update($data);
        } else {
            MapSetting::create($data);
        }

        return redirect()->route('admin.map-settings.index')->with('success', 'Map settings updated successfully!');
    }
}
