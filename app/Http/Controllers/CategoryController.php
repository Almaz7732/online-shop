<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::with('parent')->select(['id', 'name', 'slug', 'parent_id', 'created_at']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('parent_name', function($row){
                    return $row->parent ? $row->parent->name : 'Root Category';
                })
                ->addColumn('full_path', function($row){
                    return $row->full_name;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="'.route('categories.edit', $row->id).'" class="edit btn btn-success btn-sm">Edit</a> ';
                    $actionBtn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="'.$row->id.'" onclick="deleteCategory('.$row->id.')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')->with('children')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'parent_id' => 'nullable|exists:categories,id|not_in:'.$category->id
        ]);

        if ($request->parent_id && $this->isChildOfCategory($category->id, $request->parent_id)) {
            return back()->withErrors(['parent_id' => 'Cannot set a child category as parent.']);
        }

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['success' => 'Category deleted successfully!']);
    }

    private function isChildOfCategory($parentId, $childId)
    {
        $child = Category::find($childId);

        while ($child && $child->parent_id) {
            if ($child->parent_id == $parentId) {
                return true;
            }
            $child = Category::find($child->parent_id);
        }

        return false;
    }
}