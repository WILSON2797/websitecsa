<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Submit an inquiry from the contact form.
     */
    public function submitInquiry(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'telp' => 'nullable|string|max:50',
            'layanan' => 'nullable|string|max:255',
            'pesan' => 'required|string',
        ]);

        $inquiry = ContactInquiry::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Permintaan Anda telah diterima. Tim kami akan menghubungi Anda segera.',
            'data' => $inquiry
        ]);
    }

    /**
     * Get all contact inquiries (admin only).
     */
    public function getInquiries()
    {
        $inquiries = ContactInquiry::orderBy('created_at', 'desc')->get();
        return response()->json($inquiries);
    }
}
