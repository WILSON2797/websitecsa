<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsContent;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function index()
    {
        $content = \Illuminate\Support\Facades\Cache::rememberForever('cms_content', function () {
            return CmsContent::all()->pluck('value', 'key');
        });
        
        return response()->json($content);
    }

    public function updateBatch(Request $request)
    {
        $data = $request->validate([
            'contents' => 'required|array',
            'contents.*.key' => 'required|string',
            'contents.*.value' => 'nullable|string',
        ]);

        foreach ($data['contents'] as $item) {
            CmsContent::updateOrCreate(
                ['key' => $item['key']],
                ['value' => $item['value']]
            );
        }

        // Hapus cache lama agar pengunjung mendapatkan data terbaru
        \Illuminate\Support\Facades\Cache::forget('cms_content');

        return response()->json(['message' => 'CMS updated successfully']);
    }
}
