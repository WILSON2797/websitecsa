<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tag' => 'required|string|max:255',
            'desc' => 'required|string',
            'specs' => 'nullable|array',
            'icon' => 'nullable|string|max:255',
            'img' => 'nullable|image|max:15360', // up to 15MB
            'tolerance' => 'nullable|string|max:255',
            'capacity' => 'nullable|string|max:255',
            'speed' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:255',
            'auxiliary' => 'nullable|string',
            'safety' => 'nullable|string',
            'typical' => 'nullable|string',
        ]);

        if ($request->hasFile('img')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('img'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('products/' . $filename, (string) $encoded);
            $validated['img'] = '/storage/products/' . $filename;
        }
        if (!isset($validated['specs']) || is_null($validated['specs'])) {
            $validated['specs'] = [];
        }
        if (!isset($validated['icon']) || is_null($validated['icon'])) {
            $validated['icon'] = '';
        }

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tag' => 'required|string|max:255',
            'desc' => 'required|string',
            'specs' => 'nullable|array',
            'icon' => 'nullable|string|max:255',
            'img' => 'nullable|image|max:15360', // up to 15MB
            'tolerance' => 'nullable|string|max:255',
            'capacity' => 'nullable|string|max:255',
            'speed' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:255',
            'auxiliary' => 'nullable|string',
            'safety' => 'nullable|string',
            'typical' => 'nullable|string',
        ]);

        if ($request->hasFile('img')) {
            // Delete old image if exists
            if ($product->img) {
                $oldPath = str_replace('/storage/', '', $product->img);
                Storage::disk('public')->delete($oldPath);
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('img'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('products/' . $filename, (string) $encoded);
            $validated['img'] = '/storage/products/' . $filename;
        }
        if (!isset($validated['specs']) || is_null($validated['specs'])) {
            $validated['specs'] = [];
        }
        if (!isset($validated['icon']) || is_null($validated['icon'])) {
            $validated['icon'] = '';
        }

        $product->update($validated);
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->img) {
            $oldPath = str_replace('/storage/', '', $product->img);
            Storage::disk('public')->delete($oldPath);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
