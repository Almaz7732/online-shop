<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use DataTables;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = About::select(['id', 'title', 'is_active', 'created_at']);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $badge = $row->is_active ? 'success' : 'secondary';
                    $text = $row->is_active ? 'Active' : 'Inactive';
                    return '<span class="badge bg-'.$badge.'">'.$text.'</span>';
                })
                ->addColumn('content_preview', function($row){
                    return \Str::limit(strip_tags($row->content), 100);
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="'.route('admin.about.edit', $row->id).'" class="edit btn btn-success btn-sm">Edit</a> ';
                    $actionBtn .= '<button type="button" class="delete btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.about.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.about.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable|in:on'
        ]);

        $data = $request->only(['title', 'content']);
        $data['is_active'] = $request->has('is_active');

        About::create($data);

        return redirect()->route('admin.about.index')->with('success', 'About content created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(About $about)
    {
        return view('admin.about.show', compact('about'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(About $about)
    {
        return view('admin.about.edit', compact('about'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, About $about)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable|in:on'
        ]);

        $data = $request->only(['title', 'content']);
        $data['is_active'] = $request->has('is_active');

        $about->update($data);

        return redirect()->route('admin.about.index')->with('success', 'About content updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about)
    {
        $about->delete();
        return response()->json(['success' => 'About content deleted successfully!']);
    }
}
