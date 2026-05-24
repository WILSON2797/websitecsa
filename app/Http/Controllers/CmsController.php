<?php

namespace App\Http\Controllers;

use App\Models\CmsContent;
use App\Models\Product;
use App\Models\Machine;
use App\Models\Client;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    /**
     * Get all CMS content mapped as key-value pairs.
     */
    public function getContent()
    {
        $contents = CmsContent::all();
        $mapped = [];

        foreach ($contents as $content) {
            $mapped[$content->key] = $content->value;
        }

        // Load relational data dynamically from separate tables
        $mapped['products_list'] = Product::orderBy('id', 'asc')->get();
        $mapped['machines_list'] = Machine::orderBy('id', 'asc')->get();
        $mapped['clients_list'] = Client::orderBy('id', 'asc')->get();
        $mapped['facilities_list'] = \App\Models\Facility::orderBy('id', 'asc')->get();
        $mapped['galleries_list'] = \App\Models\Gallery::orderBy('display_order', 'asc')->get();

        return response()->json($mapped);
    }

    /**
     * Update CMS content.
     */
    public function updateContent(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'nullable|string',
        ]);

        $content = CmsContent::updateOrCreate(
            ['key' => $request->key],
            ['value' => $request->value]
        );

        return response()->json([
            'success' => true,
            'message' => 'Content updated successfully.',
            'data' => $content
        ]);
    }
}
