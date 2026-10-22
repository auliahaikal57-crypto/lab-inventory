<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');

        if ($search) {
            $items = Item::where('name', 'LIKE', "%{$search}%")
                         ->orWhere('brand', 'LIKE', "%{$search}%")
                         ->get();
        } else {
            $items = Item::all();
        }

        return view('katalog', compact('items', 'search'));
    }

    // PROSES TAMBAH ALAT / ITEM
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'brand'    => 'nullable|string|max:255',
            'quantity' => 'required|integer',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->back()->with('success', 'Data alat berhasil ditambahkan!');
    }

    // PROSES EDIT ALAT / ITEM
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'brand'    => 'nullable|string|max:255',
            'quantity' => 'required|integer',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($item->foto && Storage::disk('public')->exists($item->foto)) {
                Storage::disk('public')->delete($item->foto);
            }
            // Simpan foto baru
            $data['foto'] = $request->file('foto')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->back()->with('success', 'Data alat berhasil diperbarui!');
    }
}