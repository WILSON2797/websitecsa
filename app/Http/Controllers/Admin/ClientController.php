<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'logo_path' => 'nullable|image|max:15360',
        ]);

        if ($request->hasFile('logo_path')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('logo_path'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('clients/' . $filename, (string) $encoded);
            $validated['logo_path'] = '/storage/clients/' . $filename;
        }

        $client = Client::create($validated);
        return response()->json($client, 201);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'logo_path' => 'nullable|image|max:15360',
        ]);

        if ($request->hasFile('logo_path')) {
            if ($client->logo_path) {
                $oldPath = str_replace('/storage/', '', $client->logo_path);
                Storage::disk('public')->delete($oldPath);
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('logo_path'));
            $encoded = $image->toWebp(80);
            $filename = uniqid() . '.webp';
            Storage::disk('public')->put('clients/' . $filename, (string) $encoded);
            $validated['logo_path'] = '/storage/clients/' . $filename;
        }

        $client->update($validated);
        return response()->json($client);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        if ($client->logo_path) {
            $oldPath = str_replace('/storage/', '', $client->logo_path);
            Storage::disk('public')->delete($oldPath);
        }
        $client->delete();
        return response()->json(['message' => 'Client deleted successfully']);
    }
}
