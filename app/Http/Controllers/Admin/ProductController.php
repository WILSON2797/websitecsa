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
    public function index(Request $request)
    {
        $query = Product::orderBy('id', 'desc');

        if ($search = $request->input('search')) {
            foreach ($search as $field => $value) {
                if ($value && in_array($field, ['id', 'name', 'tag', 'tolerance'])) {
                    $query->where($field, 'like', "%{$value}%");
                }
            }
        }

        if ($request->is('api/admin/*')) {
            return response()->json($query->paginate(10));
        }

        return response()->json($query->get());
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
            'detail_img1' => 'nullable|image|max:15360',
            'detail_img2' => 'nullable|image|max:15360',
            'detail_img3' => 'nullable|image|max:15360',
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
            $validated['img'] = '/uploads/products/' . $filename;
        }

        foreach (['detail_img1', 'detail_img2', 'detail_img3'] as $field) {
            if ($request->hasFile($field)) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($request->file($field));
                $encoded = $image->toWebp(80);
                $filename = uniqid() . '_' . $field . '.webp';
                Storage::disk('public')->put('products/' . $filename, (string) $encoded);
                $validated[$field] = '/uploads/products/' . $filename;
            }
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
            'detail_img1' => 'nullable|image|max:15360',
            'detail_img2' => 'nullable|image|max:15360',
            'detail_img3' => 'nullable|image|max:15360',
            'tolerance' => 'nullable|string|max:255',
            'capacity' => 'nullable|string|max:255',
            'speed' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:255',
            'auxiliary' => 'nullable|string',
            'safety' => 'nullable|string',
            'typical' => 'nullable|string',
        ]);

        if ($request->hasFile('img')) {
            if ($product->img) {
                $oldPath = str_replace('/uploads/', '', $product->img);
                Storage::disk('public')->delete($oldPath);
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('img'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('products/' . $filename, (string) $encoded);
            $validated['img'] = '/uploads/products/' . $filename;
        } elseif ($request->has('remove_img') && $request->remove_img == '1') {
            if ($product->img) {
                $oldPath = str_replace('/uploads/', '', $product->img);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['img'] = null;
        }

        foreach (['detail_img1', 'detail_img2', 'detail_img3'] as $field) {
            if ($request->hasFile($field)) {
                if ($product->$field) {
                    $oldPath = str_replace('/uploads/', '', $product->$field);
                    Storage::disk('public')->delete($oldPath);
                }
                $manager = new ImageManager(new Driver());
                $image = $manager->read($request->file($field));
                $encoded = $image->toWebp(80);
                $filename = uniqid() . '_' . $field . '.webp';
                Storage::disk('public')->put('products/' . $filename, (string) $encoded);
                $validated[$field] = '/uploads/products/' . $filename;
            } elseif ($request->has("remove_{$field}") && $request->input("remove_{$field}") == '1') {
                if ($product->$field) {
                    $oldPath = str_replace('/uploads/', '', $product->$field);
                    Storage::disk('public')->delete($oldPath);
                }
                $validated[$field] = null;
            }
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
            $oldPath = str_replace('/uploads/', '', $product->img);
            Storage::disk('public')->delete($oldPath);
        }
        foreach (['detail_img1', 'detail_img2', 'detail_img3'] as $field) {
            if ($product->$field) {
                $oldPath = str_replace('/uploads/', '', $product->$field);
                Storage::disk('public')->delete($oldPath);
            }
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
