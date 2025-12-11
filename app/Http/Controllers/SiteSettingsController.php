<?php

namespace App\Http\Controllers;

use App\Models\MainSetting;
use App\Models\CarouselSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\SettingsHelper;
use DataTables;

class SiteSettingsController extends Controller
{
    /**
     * Display site settings dashboard
     */
    public function index()
    {
        $generalSettings = MainSetting::whereIn('key', [
            'site_phone', 'site_email', 'site_address', 'site_instagram',
            'site_facebook', 'site_twitter', 'site_youtube'
        ])->get()->keyBy('key');

        $carouselSlides = CarouselSlide::active()->ordered()->get();

        return view('admin.site-settings.index', compact('generalSettings', 'carouselSlides'));
    }

    /**
     * Update general settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_phone' => 'nullable|string|max:20',
            'site_email' => 'nullable|email|max:100',
            'site_address' => 'nullable|string|max:255',
            'site_instagram' => 'nullable|url|max:255',
            'site_facebook' => 'nullable|url|max:255',
            'site_twitter' => 'nullable|url|max:255',
            'site_youtube' => 'nullable|url|max:255',
        ]);

        $updatedCount = 0;
        foreach ($request->only([
            'site_phone', 'site_email', 'site_address', 'site_instagram',
            'site_facebook', 'site_twitter', 'site_youtube'
        ]) as $key => $value) {
            if ($value !== null) {
                MainSetting::setValue($key, $value, 'text', ucfirst(str_replace('site_', '', $key)));
                $updatedCount++;
            }
        }

        // Additional cache clearing to ensure immediate effect
        SettingsHelper::clearCache();
        MainSetting::clearAllCache();

        $message = "Settings updated successfully! ({$updatedCount} settings updated, cache cleared)";
        return redirect()->back()->with('success', $message);
    }

    /**
     * Display carousel slides with DataTables
     */
    public function carouselData(Request $request)
    {
        if ($request->ajax()) {
            $data = CarouselSlide::select(['id', 'title', 'subtitle', 'image_path', 'order', 'is_active', 'created_at']);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function($row){
                    if ($row->image_path) {
                        return '<img src="'.Storage::url($row->image_path).'" class="rounded" style="width: 80px; height: 50px; object-fit: cover;">';
                    }
                    return '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 50px;"><i class="bx bx-image text-muted"></i></div>';
                })
                ->addColumn('status', function($row){
                    $badge = $row->is_active ? 'success' : 'secondary';
                    $text = $row->is_active ? 'Active' : 'Inactive';
                    return '<span class="badge bg-'.$badge.'">'.$text.'</span>';
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="'.route('site-settings.carousel.edit', $row->id).'" class="edit btn btn-success btn-sm">Edit</a> ';
                    $actionBtn .= '<button type="button" class="delete btn btn-danger btn-sm" onclick="deleteSlide('.$row->id.')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'image', 'status'])
                ->make(true);
        }
    }

    /**
     * Show carousel create form
     */
    public function createCarousel()
    {
        return view('admin.site-settings.carousel.create');
    }

    /**
     * Store carousel slide
     */
    public function storeCarousel(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|url|max:255',
            'text_color' => 'nullable|string|max:7',
            'background_overlay' => 'nullable|string|max:20',
            'order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|in:on'
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Ensure directory exists
            Storage::makeDirectory('public/carousel');

            $filename = time() . '_' . $file->getClientOriginalName();
            $data['image_path'] = $file->storeAs('carousel', $filename, 'public');
        }

        CarouselSlide::create($data);

        // Clear carousel cache
        SettingsHelper::clearCache();

        return redirect()->route('site-settings.index')->with('success', 'Carousel slide created successfully! (Cache cleared)');
    }

    /**
     * Show carousel edit form
     */
    public function editCarousel(CarouselSlide $slide)
    {
        return view('admin.site-settings.carousel.edit', compact('slide'));
    }

    /**
     * Update carousel slide
     */
    public function updateCarousel(Request $request, CarouselSlide $slide)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|url|max:255',
            'text_color' => 'nullable|string|max:7',
            'background_overlay' => 'nullable|string|max:20',
            'order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|in:on'
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($slide->image_path) {
                Storage::delete('public/' . $slide->image_path);
            }

            // Ensure directory exists
//            Storage::makeDirectory('public/carousel');

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['image_path'] = $file->storeAs('carousel', $filename, 'public');
        }

        $slide->update($data);

        // Clear carousel cache
        SettingsHelper::clearCache();

        return redirect()->route('site-settings.index')->with('success', 'Carousel slide updated successfully! (Cache cleared)');
    }

    /**
     * Delete carousel slide
     */
    public function destroyCarousel(CarouselSlide $slide)
    {
        // Delete image file
        if ($slide->image_path) {
            Storage::delete('public/' . $slide->image_path);
        }

        $slide->delete();

        // Clear carousel cache
        SettingsHelper::clearCache();

        return response()->json(['success' => 'Carousel slide deleted successfully! (Cache cleared)']);
    }
}
