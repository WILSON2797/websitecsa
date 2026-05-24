<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::orderBy('id', 'asc');

        if ($search = $request->input('search')) {
            foreach ($search as $field => $value) {
                if ($value && in_array($field, ['id', 'name'])) {
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
            'short_desc' => 'nullable|string',
            'long_desc' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'img' => 'nullable|image|max:15360',
            'spec' => 'nullable|string',
        ]);

        if ($request->hasFile('img')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('img'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('facilities/' . $filename, (string) $encoded);
            $validated['img'] = '/uploads/facilities/' . $filename;
        }

        $facility = Facility::create($validated);
        return response()->json($facility, 201);
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_desc' => 'nullable|string',
            'long_desc' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'img' => 'nullable|image|max:15360',
            'spec' => 'nullable|string',
        ]);

        if ($request->hasFile('img')) {
            if ($facility->img) {
                $oldPath = str_replace('/uploads/', '', $facility->img);
                Storage::disk('public')->delete($oldPath);
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('img'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('facilities/' . $filename, (string) $encoded);
            $validated['img'] = '/uploads/facilities/' . $filename;
        } elseif ($request->has('remove_img') && $request->remove_img == '1') {
            if ($facility->img) {
                $oldPath = str_replace('/uploads/', '', $facility->img);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['img'] = null;
        }

        $facility->update($validated);
        return response()->json($facility);
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);
        if ($facility->img) {
            $oldPath = str_replace('/uploads/', '', $facility->img);
            Storage::disk('public')->delete($oldPath);
        }
        $facility->delete();
        return response()->json(['message' => 'Facility deleted successfully']);
    }
}
