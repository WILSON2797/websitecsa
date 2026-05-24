<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::orderBy('display_order')->get();
        return response()->json($galleries);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image' => 'required|image|max:5120', // Max 5MB
            'display_order' => 'integer'
        ]);

        $path = $request->file('image')->store('galleries', 'public');

        $gallery = Gallery::create([
            'title' => $request->title,
            'category' => $request->category ?? 'Operations',
            'description' => $request->description,
            'image_url' => '/uploads/' . $path,
            'display_order' => $request->display_order ?? 0
        ]);

        return response()->json($gallery, 201);
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'display_order' => 'integer'
        ]);

        if ($request->hasFile('image')) {
            $request->validate(['image' => 'image|max:5120']);
            
            // Delete old image
            $oldPath = str_replace('/uploads/', '', $gallery->image_url);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image')->store('galleries', 'public');
            $gallery->image_url = '/uploads/' . $path;
        }

        $gallery->title = $request->title;
        if ($request->has('category')) {
            $gallery->category = $request->category;
        }
        $gallery->description = $request->description;
        if ($request->has('display_order')) {
            $gallery->display_order = $request->display_order;
        }
        
        $gallery->save();

        return response()->json($gallery);
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        
        // Delete image
        $oldPath = str_replace('/uploads/', '', $gallery->image_url);
        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
        
        $gallery->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
