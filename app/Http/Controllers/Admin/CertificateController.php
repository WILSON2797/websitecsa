<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::orderBy('order')->orderBy('id', 'desc');

        if ($search = $request->input('search')) {
            foreach ($search as $field => $value) {
                if ($value && in_array($field, ['id', 'name', 'desc'])) {
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
            'desc' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'img' => 'nullable|image|max:15360',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('img')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('img'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('certificates/' . $filename, (string) $encoded);
            $validated['img'] = '/uploads/certificates/' . $filename;
        }

        if (!isset($validated['order']) || is_null($validated['order'])) {
            $validated['order'] = 0;
        }

        $certificate = Certificate::create($validated);
        return response()->json($certificate, 201);
    }

    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'img' => 'nullable|image|max:15360',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('img')) {
            if ($certificate->img && file_exists(public_path($certificate->img))) {
                unlink(public_path($certificate->img));
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('img'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('certificates/' . $filename, (string) $encoded);
            $validated['img'] = '/uploads/certificates/' . $filename;
        } elseif ($request->has('remove_img') && $request->remove_img == '1') {
            if ($certificate->img && file_exists(public_path($certificate->img))) {
                unlink(public_path($certificate->img));
            }
            $validated['img'] = null;
        }

        if (!isset($validated['order']) || is_null($validated['order'])) {
            $validated['order'] = 0;
        }

        $certificate->update($validated);
        return response()->json($certificate);
    }

    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        
        if ($certificate->img && file_exists(public_path($certificate->img))) {
            unlink(public_path($certificate->img));
        }
        
        $certificate->delete();
        return response()->json(['message' => 'Certificate deleted']);
    }
}
