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
    public function index()
    {
        return response()->json(Machine::orderBy('id')->get());
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
            $validated['img'] = '/storage/machines/' . $filename;
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
                $oldPath = str_replace('/storage/', '', $machine->img);
                Storage::disk('public')->delete($oldPath);
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('img'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('machines/' . $filename, (string) $encoded);
            $validated['img'] = '/storage/machines/' . $filename;
        }

        $machine->update($validated);
        return response()->json($machine);
    }

    public function destroy($id)
    {
        $machine = Machine::findOrFail($id);
        if ($machine->img) {
            $oldPath = str_replace('/storage/', '', $machine->img);
            Storage::disk('public')->delete($oldPath);
        }
        $machine->delete();
        return response()->json(['message' => 'Machine deleted successfully']);
    }
}
