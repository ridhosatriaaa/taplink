<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrTag;

class AdminQrTagController extends Controller
{
    // 1. Menampilkan Semua Data Tag + Pencarian
    public function index(Request $request)
    {
        $query = QrTag::query();

        // Fitur pencarian berdasarkan ID, Place ID, atau No WA
        if ($request->has('search') && $request->search != '') {
            $query->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('place_id', 'like', '%' . $request->search . '%')
                  ->orWhere('whatsapp_number', 'like', '%' . $request->search . '%');
        }

        $tags = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.index', compact('tags'));
    }

    // 2. Membuat data papan massal (Bulk Generate)
    public function generate(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:500'
        ]);

        $lastId = (int) QrTag::max('id');
        $start = $lastId ?: 0;

        for ($i = 0; $i < $request->count; $i++) {
            $currentId = str_pad($start + $i + 1, 3, '0', STR_PAD_LEFT);

            QrTag::create([
                'id'             => $currentId,
                'activation_pin' => null,
                'is_active'      => false,
            ]);
        }

        return back()->with('success', $request->count . ' Papan QR berhasil dibuat!');
    }

    // 3. Upload Template Canva Background
    public function uploadTemplate(Request $request)
    {
        $request->validate([
            'template' => 'required|image|mimes:png,jpg,jpeg|max:5096',
        ]);

        if ($request->hasFile('template')) {
            $file = $request->file('template');
            $destinationPath = public_path('images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $file->move($destinationPath, 'template-canva.png');
        }

        return back()->with('success', 'Template Canva berhasil diperbarui!');
    }

    // 4. Reset Status & PIN Tag (Jika pelanggan lupa PIN)
    public function reset($id)
    {
        $qrTag = QrTag::findOrFail($id);
        $qrTag->update([
            'place_id'        => null,
            'whatsapp_number' => null,
            'activation_pin'  => null,
            'is_active'       => false,
        ]);

        return back()->with('success', "Tag ID {$id} berhasil di-reset ke status non-aktif!");
    }

    // 5. Hapus Tag
    public function destroy($id)
    {
        $qrTag = QrTag::findOrFail($id);
        $qrTag->delete();

        return back()->with('success', "Tag ID {$id} berhasil dihapus!");
    }
}