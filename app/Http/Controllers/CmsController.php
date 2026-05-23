<?php

namespace App\Http\Controllers;

use App\Models\CmsContent;
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
