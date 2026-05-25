<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MachineController extends Controller
{
    public function index(Request $request)
    {
        $query = Machine::orderBy('id', 'asc');

        if ($search = $request->input('search')) {
            foreach ($search as $field => $value) {
                if ($value && in_array($field, ['id', 'name', 'spec', 'qty'])) {
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
            'spec' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
            'icon' => 'nullable|string|max:255',
            'img' => 'nullable|image|max:15360',
            'longDesc' => 'nullable|string',
            'origin' => 'nullable|string|max:255',
            'precision' => 'nullable|string|max:255',
            'safety' => 'nullable|string',
            'application' => 'nullable|string',
        ]);

        if ($request->hasFile('img')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('img'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('machines/' . $filename, (string) $encoded);
            $validated['img'] = '/uploads/machines/' . $filename;
        }

        $machine = Machine::create($validated);
        return response()->json($machine, 201);
    }

    public function update(Request $request, $id)
    {
        $machine = Machine::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'spec' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
            'icon' => 'nullable|string|max:255',
            'img' => 'nullable|image|max:15360',
            'longDesc' => 'nullable|string',
            'origin' => 'nullable|string|max:255',
            'precision' => 'nullable|string|max:255',
            'safety' => 'nullable|string',
            'application' => 'nullable|string',
        ]);

        if ($request->hasFile('img')) {
            if ($machine->img) {
                $oldPath = str_replace('/uploads/', '', $machine->img);
                Storage::disk('public')->delete($oldPath);
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('img'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('machines/' . $filename, (string) $encoded);
            $validated['img'] = '/uploads/machines/' . $filename;
        } elseif ($request->has('remove_img') && $request->remove_img == '1') {
            if ($machine->img) {
                $oldPath = str_replace('/uploads/', '', $machine->img);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['img'] = null;
        }

        $machine->update($validated);
        return response()->json($machine);
    }

    public function destroy($id)
    {
        $machine = Machine::findOrFail($id);
        if ($machine->img) {
            $oldPath = str_replace('/uploads/', '', $machine->img);
            Storage::disk('public')->delete($oldPath);
        }
        $machine->delete();
        return response()->json(['message' => 'Machine deleted successfully']);
    }
}
