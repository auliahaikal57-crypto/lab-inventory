<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Lab Biomedis STIE ITB</title>
    <style>
        body { background-color: #f3f4f6; font-family: Arial, sans-serif; margin: 0; padding: 40px; color: #333; }
        .container { max-width: 1100px; margin: 0 auto; }
        
        /* Barisan Tombol Pill Kategori */
        .filter-container { display: flex; gap: 10px; margin-bottom: 30px; flex-wrap: wrap; }
        .pill { 
            background-color: white; color: #4b5563; border: 1px solid #e5e7eb; 
            padding: 8px 18px; border-radius: 20px; font-size: 14px; font-weight: bold; 
            cursor: pointer; transition: all 0.2s; 
        }
        .pill:hover { background-color: #f9fafb; border-color: #d1d5db; }
        .pill.active { background-color: #1e293b; color: white; border-color: #1e293b; }
        .pill.green { color: #065f46; border-color: #a7f3d0; }
        .pill.green.active { background-color: #059669; color: white; }
        .pill.red { color: #991b1b; border-color: #fecaca; }
        .pill.red.active { background-color: #dc2626; color: white; }

        /* Area Foto Card */
.card-image-wrapper {
    position: relative;
    width: 100%;
    height: 180px;
    background-color: #f3f4f6;
    overflow: hidden;
}
.card-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.card-no-image {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #9ca3af;
    font-size: 13px;
}
.badge-floating { position: absolute; top: 12px; left: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }

        /* Pencarian & Grid */
        .search-form { display: flex; gap: 10px; margin-bottom: 30px; }
        .search-input { width: 300px; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
        .btn-cari { background-color: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .card { background-color: white; padding: 24px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between; }
        
        /* Badge Status */
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; margin-bottom: 12px; }
        .status-tersedia { background-color: #d1fae5; color: #065f46; }
        .status-kosong { background-color: #fee2e2; color: #991b1b; }
        .status-booking { background-color: #fef3c7; color: #b45309; }
        
        .card-footer { border-top: 1px solid #edf2f7; padding-top: 12px; font-size: 12px; color: #6b7280; margin-top: auto; }
        .btn-pinjam { background-color: #2563eb; color: white; border: none; padding: 8px 12px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; font-size: 12px; margin-top: 10px; text-align: center; text-decoration: none; display: block; box-sizing: border-box; }
        .btn-disabled { background-color: #d1d5db; color: #9ca3af; border: none; padding: 8px 12px; border-radius: 4px; width: 100%; margin-top: 10px; font-size: 12px; cursor: not-allowed; }

        /* Panel Konten Beranda */
        .beranda-panel { background: white; padding: 30px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05); line-height: 1.6; }
        
        /* CLASS SENGATAN ANTI-BENTROK 🛡️ */
        .hidden-panel { display: none !important; }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- Header Utama -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 style="margin: 0; color: #1e293b;">Lab Biomedis & Gedung STIE ITB</h1>

    <div style="display: flex; align-items: center; gap: 12px;">
        <!-- TOMBOL KERANJANG BARU -->
        <a href="{{ route('cart.index') }}" style="background-color: #2563eb; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px; display: flex; align-items: center; gap: 6px;">
            🛒 Keranjang
            <span style="background-color: white; color: #2563eb; border-radius: 50%; padding: 2px 8px; font-size: 12px; font-weight: bold;">
                {{ count(session('cart', [])) }}
            </span>
        </a>

        <!-- TOMBOL PENGEMBALIAN BARANG (Bila Mahasiswa Punya Pinjaman Aktif) -->
    @php
        $myActiveLoansCount = \App\Models\Loan::where('user_id', auth()->id())
            ->where('loan_status', 'Aktif')
            ->count();
    @endphp

    @if($myActiveLoansCount > 0)
        <button id="btn-tab-pengembalian" type="button" class="pill" style="background-color: #10b981; color: white; border: none; padding: 10px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px;">
            🔄 Kembalikan Alat ({{ $myActiveLoansCount }})
        </button>
    @endif

        <span style="font-weight: bold; color: #4b5563; font-size: 16px;">
            Halo, {{ Auth::user()->name }}
        </span>

        <form method="POST" action="/logout" style="margin: 0;">
            @csrf
            <button type="submit" style="background-color: #4b5563; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Keluar (Log Out)</button>
        </form>
    </div>
</div>

        
        <!-- BARISAN TOMBOL PILL NAVIGASI -->
        <div class="filter-container">
            <button id="btn-tab-beranda" type="button" class="pill active">Beranda Lab / Gedung STIE ITB</button>
            <button id="btn-tab-semua" type="button" class="pill">Semua Peminjaman Alat</button>
            <button id="btn-tab-tersedia" type="button" class="pill green">● Tersedia</button>
            <button id="btn-tab-kosong" type="button" class="pill red">● Kosong</button>
        </div>

        <!-- ================= PANEL 1: BERANDA LAB / GEDUNG STIE ITB ================= -->
        <div id="panel-beranda" class="beranda-panel">
            <h2>Profil Gedung & Laboratorium STIE ITB</h2>
            <p>Selamat datang di sistem inventaris digital Gedung STIE ITB. Laboratorium Biomedis merupakan fasilitas riset utama yang digunakan untuk menunjang kegiatan praktikum mahasiswa, penelitian dosen, serta pengembangan inovasi teknologi kesehatan terapan.</p>
            <h3>Fasilitas Utama Gedung:</h3>
            <ul>
                <li><strong>Lantai 1:</strong> Ruang Administrasi, Aula Utama, dan Ruang Inventaris Logistik.</li>
                <li><strong>Lantai 2:</strong> Laboratorium Biomedis Utama, Ruang Asisten Lab, dan Area Penyimpanan Mikroskop.</li>
                <li><strong>Lantai 3:</strong> Ruang Teori, Ruang Riset Mandiri, dan Perpustakaan Jurnal Biomedika.</li>
            </ul>
            <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">*Jam Operasional Peminjaman Alat Lab: Senin - Jumat (08.00 - 16.00 WIB)</p>
        </div>

        <!-- ================= PANEL 2: KATALOG / PEMINJAMAN ALAT ================= -->
        <div id="panel-katalog" class="beranda-panel hidden-panel">
            <!-- Form Pencarian (?q=...) -->
            <form action="/katalog" method="GET" class="search-form">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari alat praktikum..." class="search-input">
                <button type="submit" class="btn-cari">Cari</button>
                @if($search)
                    <a href="/katalog" style="background-color:#9ca3af; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:bold; display:flex; align-items:center;">Reset</a>
                @endif
            </form>

            <!-- Grid Kartu Barang -->
            <div class="grid">
    @forelse($items as $item)
    @php
        // Ambil data peminjaman aktif untuk barang ini
        $loanStatus = \App\Models\Loan::where('item_id', $item->id)
            ->whereIn('loan_status', ['Pending', 'Disetujui', 'Dipinjam'])
            ->latest()
            ->first();
    @endphp

    <div class="card item-card" data-status="{{ $item->quantity == 0 ? 'kosong' : 'tersedia' }}">
        
        <!-- Foto Card -->
        <div class="card-image-wrapper">
            @if($item->foto)
                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->name }}">
            @else
                <div class="card-no-image">
                    <span>Tidak ada foto</span>
                </div>
            @endif
        </div>

        <!-- Detail Barang -->
        <div style="padding: 20px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <!-- Status Badge Dinamis -->
                @if($loanStatus && $loanStatus->loan_status === 'Pending')
                    <span class="badge status-booking">⏳ Di-booking (Menunggu ACC)</span>
                @elseif($loanStatus && $loanStatus->loan_status === 'Disetujui')
                    <span class="badge" style="background-color: #dbeafe; color: #1e40af;">✅ Disetujui (Menunggu Scan QR)</span>
                @elseif($loanStatus && $loanStatus->loan_status === 'Dipinjam')
                    <span class="badge status-kosong">🔴 Sedang Dipinjam</span>
                @elseif($item->quantity == 0)
                    <span class="badge status-kosong">Kosong</span>
                @else
                    <span class="badge status-tersedia">Tersedia (Sisa: {{ $item->quantity }} {{ $item->unit }})</span>
                @endif

                <h3 style="font-size:18px; margin:10px 0 4px 0; font-weight:bold;">{{ $item->name }}</h3>
                <p style="color:#6b7280; font-size:13px; margin:0 0 12px 0;">{{ $item->brand }} {{ $item->type }}</p>
            </div>

            <div class="card-footer">
                <div>Kode Serial: <span style="font-family:monospace; font-weight:bold; color:#374151;">{{ $item->serial_code }}</span></div>
                
                @if($item->quantity > 0)
                    <form action="{{ route('cart.add', $item->id) }}" method="POST" style="margin-top: 10px;">
                        @csrf
                        <button type="submit" class="btn-pinjam" style="background-color: #2563eb; width: 100%; border: none; padding: 10px; border-radius: 6px; color: white; font-weight: bold; cursor: pointer;">
                            + Tambah ke Keranjang
                        </button>
                    </form>
                @else
                    <button disabled class="btn-disabled" style="width: 100%; margin-top: 10px; padding: 10px; border-radius: 6px; background-color: #d1d5db; color: #9ca3af; border: none; cursor: not-allowed;">
                        Stok Habis
                    </button>
                @endif
            </div>
        </div>

    </div>
@empty
    <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 30px;">
        <p style="color: #6b7280; font-weight: bold; margin: 0;">Barang tidak ditemukan.</p>
    </div>
@endforelse
</div>
        </div>
    </div>

    <!-- ================= PANEL 3: DAFTAR PENGEMBALIAN ALAT ================= -->
<div id="panel-pengembalian" class="beranda-panel hidden-panel">
    <h2>🔄 Daftar Alat yang Sedang Anda Pinjam</h2>
    <p style="color: #6b7280; font-size: 14px;">Klik tombol "Proses Pengembalian" untuk mengembalikan alat yang telah selesai digunakan.</p>
    
    @php
        $myLoans = \App\Models\Loan::with('item')
            ->where('user_id', auth()->id())
            ->where('loan_status', 'Aktif')
            ->get();
    @endphp

    @if($myLoans->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
            @foreach($myLoans as $loan)
                <div style="display: flex; justify-content: space-between; align-items: center; border: 1px solid #e5e7eb; padding: 15px 20px; border-radius: 8px; background: #f9fafb;">
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 16px; color: #1e293b;">{{ $loan->item->name ?? 'Barang' }}</h3>
                        <p style="margin: 0; font-size: 13px; color: #6b7280;">
                            Jumlah Pinjam: <strong>{{ $loan->borrow_quantity }} {{ $loan->item->unit ?? 'pcs' }}</strong> | 
                            Kode Serial: <code>{{ $loan->item->serial_code ?? '-' }}</code>
                        </p>
                    </div>
                    <a href="/katalog/kembali/{{ $loan->id }}" class="btn-pinjam" style="background-color: #10b981; color: white; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-weight: bold; margin: 0; width: auto;">
                        Proses Pengembalian →
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #6b7280;">
            <p style="font-weight: bold; margin: 0;">Anda tidak memiliki pinjaman alat yang aktif saat ini.</p>
        </div>
    @endif
</div>

    <!-- JAVASCRIPT PERBAIKAN -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const panelBeranda = document.getElementById('panel-beranda');
            const panelKatalog = document.getElementById('panel-katalog');
            const panelPengembalian = document.getElementById('panel-pengembalian');
            const btnPengembalian = document.getElementById('btn-tab-pengembalian');
            const semuaPill = document.querySelectorAll('.filter-container .pill');
            const itemCards = document.querySelectorAll('.item-card');

            function setAktif(tombol) {
                semuaPill.forEach(p => p.classList.remove('active'));
                tombol.classList.add('active');
            }

            function tampilkanKatalog() {
                panelBeranda.classList.add('hidden-panel');
                panelKatalog.classList.remove('hidden-panel');
            }

            if (btnPengembalian) {
        btnPengembalian.addEventListener('click', function(e) {
            e.preventDefault();
            setAktif(this);
            panelBeranda.classList.add('hidden-panel');
            panelKatalog.classList.add('hidden-panel');
            panelPengembalian.classList.remove('hidden-panel');
        });
    }

            // 1. Klik Beranda
            document.getElementById('btn-tab-beranda').addEventListener('click', function(e) {
                e.preventDefault();
                setAktif(this);
                panelBeranda.classList.remove('hidden-panel');
                panelKatalog.classList.add('hidden-panel');
            });

            // 2. Klik Semua Peminjaman
            document.getElementById('btn-tab-semua').addEventListener('click', function(e) {
                e.preventDefault();
                setAktif(this);
                tampilkanKatalog();
                itemCards.forEach(c => c.classList.remove('hidden-panel'));
            });

            // 3. Klik Filter Tersedia
            document.getElementById('btn-tab-tersedia').addEventListener('click', function(e) {
                e.preventDefault();
                setAktif(this);
                tampilkanKatalog();

                itemCards.forEach(card => {
                    if (card.getAttribute('data-status') === 'tersedia') {
                        card.classList.remove('hidden-panel');
                    } else {
                        card.classList.add('hidden-panel');
                    }
                });
            });

            // 4. Klik Filter Kosong
            document.getElementById('btn-tab-kosong').addEventListener('click', function(e) {
                e.preventDefault();
                setAktif(this);
                tampilkanKatalog();

                itemCards.forEach(card => {
                    if (card.getAttribute('data-status') === 'kosong') {
                        card.classList.remove('hidden-panel');
                    } else {
                        card.classList.add('hidden-panel');
                    }
                });
            });

            // Otomatis pindah ke tab katalog jika sedang melakukan pencarian
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.has('q')) {
                document.getElementById('btn-tab-semua').click();
            }
        });
    </script>
</body>
</html>
