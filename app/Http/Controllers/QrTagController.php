<?php

namespace App\Http\Controllers;

use App\Models\QrTag;
use Illuminate\Http\Request;

class QrTagController
{
    public function adminIndex()
    {
        $tags = QrTag::latest()->paginate(15);

        return view('admin', compact('tags'));
    }

    public function adminGenerate(Request $request)
    {
        $validated = $request->validate([
            'count' => 'nullable|integer|min:1|max:500',
            'quantity' => 'nullable|integer|min:1|max:500',
        ]);

        $quantity = $validated['count'] ?? $validated['quantity'] ?? 1;
        $maxId = QrTag::max('id');
        $start = $maxId ? (int) $maxId : 0;

        for ($i = 0; $i < $quantity; $i++) {
            $nextId = str_pad($start + $i + 1, 3, '0', STR_PAD_LEFT);

            QrTag::create([
                'id' => $nextId,
                'is_active' => false,
            ]);
        }

        return back()->with('success', "{$quantity} Papan QR berhasil ditambahkan!");
    }

    public function exportQrCsv()
    {
        $tags = QrTag::orderBy('id')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="qr-review-cards.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($tags) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['id', 'url']);

            foreach ($tags as $tag) {
                fputcsv($handle, [(string) $tag->id, route('qr.scan', $tag->id)]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function scan(QrTag $qrTag)
    {
        if ($qrTag->is_active) {
            return view('rating', compact('qrTag'));
        }

        return view('activation', compact('qrTag'));
    }

    public function activate(Request $request, QrTag $qrTag)
    {
        if ($qrTag->is_active) {
            return redirect()->route('qr.scan', $qrTag->id)
                ->with('error', 'Papan QR ini sudah diaktivasi sebelumnya.');
        }

        $validated = $request->validate([
            'place_id'        => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'activation_pin'  => 'required|digits:4',
        ]);

        $qrTag->update([
            'place_id'        => $validated['place_id'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'activation_pin'  => $validated['activation_pin'],
            'is_active'       => true,
        ]);

        return redirect()->route('qr.scan', ['qrTag' => $qrTag->id, 'status' => 'sukses']);
    }

    public function editForm(QrTag $qrTag)
    {
        return view('edit', compact('qrTag'));
    }

    public function verifyPin(Request $request, QrTag $qrTag)
    {
        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        if ((string) $request->pin === (string) $qrTag->activation_pin) {
            return response()->json([
                'success' => true,
                'message' => 'PIN Valid',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'PIN salah! Silakan coba lagi.',
        ], 401);
    }

    public function update(Request $request, QrTag $qrTag)
    {
        $request->validate([
            'current_pin'     => 'required|digits:4',
            'place_id'        => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'new_pin'         => 'nullable|digits:4',
        ]);

        if ((string) $request->current_pin !== (string) $qrTag->activation_pin) {
            return back()->with('error', 'Gagal menyimpan! Autentikasi PIN tidak valid.');
        }

        $updateData = [
            'place_id'        => $request->place_id,
            'whatsapp_number' => $request->whatsapp_number,
        ];

        if ($request->filled('new_pin')) {
            $updateData['activation_pin'] = $request->new_pin;
        }

        $qrTag->update($updateData);

        return back()->with('success', 'Informasi toko berhasil diperbarui!');
    }
}