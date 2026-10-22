<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KatalogController;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FonnteCallbackController;


// 1. Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// 2. ROUTE HALAMAN LOGIN MAHASISWA
Route::get('/login-mahasiswa', function () {
    return view('auth.login-mahasiswa');
})->middleware('guest')->name('login.mahasiswa');

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// 1. Menampilkan Halaman Register Mahasiswa
Route::get('/register-mahasiswa', function () {
    return view('auth.register-mahasiswa');
})->middleware('guest')->name('register.mahasiswa');

// 2. Memproses Pendaftaran Akun Mahasiswa Baru & Menyimpannya ke Database
Route::post('/register-mahasiswa', function (Request $request) {
    $request->validate([
        'name'        => ['required', 'string', 'max:255'],
        'email'       => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password'    => ['required', 'confirmed'],
    ]);

    // Simpan akun ke database tabel 'users'
    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'mahasiswa', // Otomatis diset sebagai Mahasiswa
    ]);

    // Otomatis login setelah berhasil mendaftar
    Auth::login($user);

    // Dengar dan arahkan langsung ke halaman Katalog
    return redirect('/katalog');
})->middleware('guest')->name('register.mahasiswa.store');


// 3. Routing Dashboard berdasarkan Role (Sudah Diperbaiki untuk Super Admin)
Route::get('/dashboard', function () {
    // Memastikan jika yang login adalah admin atau super_admin, wajib masuk ke halaman preferences admin
    if (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin') {
        return redirect('/preferences');
    }
    
    // Jika mahasiswa, arahkan ke katalog
    return redirect('/katalog');
})->middleware(['auth'])->name('dashboard');


// 4. Group Profile (Bawaan Laravel Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ==========================================
// FITUR ADMIN (PREFERENCES / KELOLA BARANG)
// ==========================================

// SESUDAH (Sudah diperbaiki agar meloloskan Super Admin 🛡️)
Route::get('/preferences', function () {
    // Izinkan masuk jika role-nya adalah 'admin' ATAU 'super_admin'
    if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'super_admin') {
        abort(403, 'Anda tidak memiliki hak akses ke halaman admin ini.');
    }
    $items = Item::all();
    return view('admin_dashboard', compact('items'));
})->middleware(['auth']);


// Aksi Tambah Barang oleh Admin (Tambahan Upload Foto + Super Admin)
Route::post('/preferences/tambah', function (Request $request) {
    if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'super_admin') { 
        abort(403); 
    }

    $request->validate([
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Maks 2MB
    ]);

    $data = $request->all();

    if ($request->hasFile('foto')) {
        $data['foto'] = $request->file('foto')->store('items', 'public');
    }

    Item::create($data);
    return redirect('/preferences');
})->middleware(['auth']);



// TAMBAHAN LANGKAH 2: Rute AJAX untuk melakukan pencocokan urutan terakhir di database
Route::get('/admin/inventaris/check-sequence', function (Request $request) {
    $prefix = $request->query('prefix');
    $year = date('y'); // Format dua digit tahun berjalan

    // Cari entri barang terakhir di database berdasarkan prefiks kategori
    $lastItem = Item::where('prefix', $prefix)
                    ->where('serial_code', 'LIKE', "%-{$year}%")
                    ->orderBy('id', 'desc')
                    ->first();

    if ($lastItem) {
        $lastSeq = intval(substr($lastItem->serial_code, -3));
        $nextSeq = $lastSeq + 1;
    } else {
        $nextSeq = 1; // Mulai dari 001 jika belum ada data kategori tersebut di tahun ini
    }

    return response()->json([
        'next_seq' => str_pad($nextSeq, 3, '0', STR_PAD_LEFT)
    ]);
})->middleware(['auth']);

// Menampilkan Halaman Edit Form Admin
// Menampilkan Halaman Edit Form Admin (Sudah izinkan super_admin 🛡️)
Route::get('/preferences/edit/{id}', function ($id) {
    if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'super_admin') { 
        abort(403, 'Anda tidak memiliki hak akses.'); 
    }
    $item = Item::findOrFail($id);
    return view('admin_edit', compact('item'));
})->middleware(['auth']);

// Aksi Simpan Edit Barang oleh Admin
Route::post('/preferences/edit/{id}', function (Request $request, $id) {
    if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'super_admin') { 
        abort(403); 
    }

    $item = Item::findOrFail($id);

    $request->validate([
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Maks 2MB
    ]);

    $data = $request->only(['name', 'brand', 'type', 'quantity', 'unit', 'serial_code', 'status']);

    if ($request->hasFile('foto')) {
        // Hapus foto lama jika ada di storage
        if ($item->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->foto)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($item->foto);
        }
        // Simpan foto baru
        $data['foto'] = $request->file('foto')->store('items', 'public');
    }

    $item->update($data);
    return redirect('/preferences');
})->middleware(['auth']);

// Aksi Hapus Barang oleh Admin
Route::get('/preferences/hapus/{id}', function ($id) {
    Item::findOrFail($id)->delete();
    return redirect('/preferences');
})->middleware(['auth']);

// ==========================================
// FITUR MAHASISWA (KATALOG & TRANSAKSI)
// ==========================================

// Menampilkan Halaman Katalog Mahasiswa
Route::get('/katalog', [KatalogController::class, 'index'])->middleware(['auth']);

// Tampilkan Formulir Pinjam
Route::get('/katalog/pinjam/{id}', function ($id) {
    $item = Item::findOrFail($id);
    return view('pinjam_form', compact('item'));
})->middleware(['auth']);

// Proses Kirim Peminjaman + Validasi Stok
Route::post('/katalog/pinjam/{id}', function (Request $request, $id) {
    $item = Item::findOrFail($id);
    
    if ($request->borrow_quantity <= 0) {
        return back()->withErrors(['msg' => 'Jumlah barang yang dipinjam tidak valid!']);
    }

    if ($request->borrow_quantity > $item->quantity) {
        return back()->withErrors(['msg' => 'Jumlah pinjaman melebihi stok tersedia!']);
    }

    $photoName = time() . '_awal_' . $request->file('borrow_photo')->getClientOriginalName();
    $request->file('borrow_photo')->move(public_path('uploads'), $photoName);

    $allNames = implode(', ', $request->student_names);
    $allNpms  = implode(', ', $request->npms);

     Loan::create([
        'user_id'          => auth()->id(), // TAMBAHAN WAJIB: Menyimpan akun mahasiswa yang sedang meminjam
        'item_id'          => $item->id,
        'loan_type'        => $request->loan_type ?? 'Pribadi',
        'group_size'       => $request->group_size ?? 1,
        'student_name'     => $allNames,
        'npm'              => $allNpms,
        'borrow_quantity'  => $request->borrow_quantity,
        'borrow_condition' => $request->borrow_condition,
        'borrow_photo'     => $photoName,
        'loan_status'      => 'Aktif'
    ]);

    $sisaStok = $item->quantity - $request->borrow_quantity;
    $statusBaru = ($sisaStok == 0) ? 'Sedang Dipinjam' : 'Tersedia';

    $item->update([
        'quantity' => $sisaStok,
        'status' => $statusBaru
    ]);

    return redirect('/katalog');
})->middleware(['auth']);

// Tampilkan Formulir Pengembalian
Route::get('/katalog/kembali/{id}', function ($id) {
    $loan = Loan::with('item')->findOrFail($id);
    return view('kembali_form', compact('loan'));
})->middleware(['auth']);

// Proses Kirim Pengembalian + Validasi Kembali
Route::post('/katalog/kembali/{id}', function (Request $request, $id) {
    $loan = Loan::findOrFail($id);
    
    if ($request->return_quantity <= 0) {
        return back()->withErrors(['msg' => 'Jumlah pengembalian tidak valid!']);
    }

    if ($request->return_quantity > $loan->borrow_quantity) {
        return back()->withErrors(['msg' => 'Jumlah yang dikembalikan melebihi jumlah yang Anda pinjam!']);
    }

    $photoName = time() . '_akhir_' . $request->file('return_photo')->getClientOriginalName();
    $request->file('return_photo')->move(public_path('uploads'), $photoName);

    $loan->update([
        'return_quantity' => $request->return_quantity,
        'return_condition' => $request->return_condition,
        'return_photo' => $photoName,
        'loan_status' => 'Selesai'
    ]);

    $stokBaru = $loan->item->quantity + $request->return_quantity;

    $loan->item->update([
        'quantity' => $stokBaru,
        'status' => 'Tersedia'
    ]);

    // PERBAIKAN RE-ROUTE: Memaksa Admin/Super Admin masuk ke preferences jika terdampar di halaman utama
Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin') {
            return redirect('/preferences');
        }
    }
    return redirect('/katalog');
})->middleware(['auth'])->name('dashboard');


    return redirect('/katalog');
})->middleware(['auth']);

// ROUTE KERANJANG PEMINJAMAN
Route::middleware(['auth'])->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/keranjang/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/keranjang/checkout', [CartController::class, 'processCheckout'])->name('cart.processCheckout');
    Route::post('/fonnte/callback', [FonnteCallbackController::class, 'handleCallback']);
});

