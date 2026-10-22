<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Services\WhatsAppService;

class CartController extends Controller
{
    // 1. Menampilkan Halaman Keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    // 2. Menambahkan Alat ke Keranjang
    public function add(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] < $item->quantity) {
                $cart[$id]['quantity']++;
            } else {
                return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia!');
            }
        } else {
            $cart[$id] = [
                'id'          => $item->id,
                'name'        => $item->name,
                'brand'       => $item->brand,
                'serial_code' => $item->serial_code,
                'unit'        => $item->unit,
                'foto'        => $item->foto,
                'quantity'    => 1,
                'max_stock'   => $item->quantity
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Alat berhasil ditambahkan ke keranjang!');
    }

    // 3. Menghapus Item dari Keranjang
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Alat berhasil dihapus dari keranjang!');
    }

    // 4. Menampilkan Form Pengajuan Peminjaman Sekaligus
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (count($cart) == 0) {
            return redirect('/katalog')->with('error', 'Keranjang peminjaman Anda kosong!');
        }

        return view('checkout_form', compact('cart'));
    }

    // 5. Memproses Simpan Peminjaman Sekaligus dengan Status Pending & Notifikasi WA
    public function processCheckout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (count($cart) == 0) {
            return redirect('/katalog');
        }

        $request->validate([
            'student_names'    => 'required|array',
            'npms'             => 'required|array',
            'borrow_photo'     => 'required|image|max:2048',
            'borrow_condition' => 'required|string',
        ]);

        // Upload Foto Awal
        $photoName = time() . '_awal_' . $request->file('borrow_photo')->getClientOriginalName();
        $request->file('borrow_photo')->move(public_path('uploads'), $photoName);

        $studentNames = $request->student_names;
        $npms         = $request->npms;

        $allNames = implode(', ', $studentNames);
        $allNpms  = implode(', ', $npms);

        // Inisialisasi variabel $lastLoan di luar loop
        $lastLoan = null;

        // Loop Simpan Setiap Barang ke Tabel Loans dengan Status 'Pending'
        foreach ($cart as $itemId => $details) {
            $item = Item::find($itemId);

            if ($item) {
                // Buat Data Loan Utama (Status Pending)
                $lastLoan = \App\Models\Loan::create([
                    'user_id'          => auth()->id(),
                    'item_id'          => $item->id,
                    'loan_type'        => $request->loan_type ?? 'Pribadi',
                    'group_size'       => count($studentNames),
                    'student_name'     => $allNames,
                    'npm'              => $allNpms,
                    'borrow_quantity'  => $details['quantity'],
                    'borrow_condition' => $request->borrow_condition,
                    'borrow_photo'     => $photoName,
                    'loan_status'      => 'Pending'
                ]);

                // Simpan Rincian Anggota Tim jika Kelompok
                if ($request->loan_type === 'Kelompok') {
                    foreach ($studentNames as $index => $name) {
                        DB::table('group_members')->insert([
                            'loan_id'      => $lastLoan->id,
                            'student_name' => $name,
                            'npm'          => $npms[$index] ?? '-',
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ]);
                    }
                }
            }
        }

        // KIRIM NOTIFIKASI WA KE ADMIN (SRS UC-09)
        $adminWA = env('ADMIN_WA_NUMBER'); 

if ($adminWA && $lastLoan) {
    $pesan = "*PENGAJUAN PEMINJAMAN ALAT BARU!* 🧪\n\n"
        . "ID Peminjaman: *#LOAN-" . $lastLoan->id . "*\n"
        . "Jenis: *" . ($request->loan_type ?? 'Pribadi') . "*\n"
        . "Pemohon/Ketua: *" . $allNames . "*\n"
        . "NIM: *" . $allNpms . "*\n"
        . "Kondisi Awal: *" . $request->borrow_condition . "*\n\n"
        . "Silakan tinjau dan tanggapi pengajuan ini.\n"
        . "Balas *ACC " . $lastLoan->id . "* untuk Menyetujui atau *REJECT " . $lastLoan->id . "* untuk Menolak.";

    WhatsAppService::sendMessage($adminWA, $pesan);
}

        // Kosongkan Keranjang
        session()->forget('cart');

        return redirect('/katalog')->with('success', 'Pengajuan peminjaman berhasil terkirim! Status saat ini: Menunggu Persetujuan Admin.');
    }
}