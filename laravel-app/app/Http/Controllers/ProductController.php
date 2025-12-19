<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with(['category', 'brand', 'primaryImage'])
                ->select(['id', 'name', 'slug', 'price', 'category_id', 'brand_id', 'created_at']);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function($row){
                    if ($row->primaryImage) {
                        return '<img src="'.Storage::url($row->primaryImage->image_path).'" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">';
                    }
                    return '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="bx bx-image text-muted"></i></div>';
                })
                ->addColumn('category_name', function($row){
                    return $row->category ? $row->category->name : 'N/A';
                })
                ->addColumn('brand_name', function($row){
                    return $row->brand ? $row->brand->name : 'N/A';
                })
                ->addColumn('price_formatted', function($row){
                    return number_format($row->price, 2) . ' СОМ';
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="'.route('products.show', $row->id).'" class="view btn btn-info btn-sm me-1">View</a>';
                    $actionBtn .= '<a href="'.route('products.edit', $row->id).'" class="edit btn btn-success btn-sm me-1">Edit</a>';
                    $actionBtn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="'.$row->id.'" onclick="deleteProduct('.$row->id.')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['category', 'brand', 'images']);
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,'.$product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id
        ]);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete all product images from storage
        foreach ($product->images as $image) {
            Storage::delete($image->image_path);
        }

        $product->delete();
        return response()->json(['success' => 'Product deleted successfully!']);
    }

    /**
     * Upload product images via Ajax
     */
    public function uploadImages(Request $request, Product $product)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('products', $filename, 'public');

        $image = ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $path,
            'is_primary' => $product->images()->count() === 0 // First image is primary
        ]);

        return response()->json([
            'success' => true,
            'image' => $image,
            'url' => Storage::url($path)
        ]);
    }

    /**
     * Delete product image
     */
    public function deleteImage(ProductImage $image)
    {
        Storage::delete($image->image_path);
        $wasPrimary = $image->is_primary;
        $productId = $image->product_id;

        $image->delete();

        // If deleted image was primary, set first remaining image as primary
        if ($wasPrimary) {
            ProductImage::where('product_id', $productId)->first()?->update(['is_primary' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Set primary image
     */
    public function setPrimaryImage(ProductImage $image)
    {
        // Remove primary status from all images of this product
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);

        // Set this image as primary
        $image->update(['is_primary' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Handle multiple image uploads
     */
    private function handleImageUploads(Product $product, $files)
    {
        $isFirst = $product->images()->count() === 0;

        foreach ($files as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('products', $filename, 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => $isFirst
            ]);

            $isFirst = false; // Only first image is primary
        }
    }
}
