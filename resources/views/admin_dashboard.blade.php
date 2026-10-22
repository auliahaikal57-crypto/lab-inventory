<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preferences - Admin Lab</title>
    <style>
        body { background-color: #f3f4f6; font-family: Arial, sans-serif; margin: 0; padding: 40px; color: #333; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1, h2 { color: #1f2937; }
        .flex-container { display: flex; gap: 40px; margin-top: 20px; }
        .form-panel { flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .list-panel { flex: 2; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-tambah { background-color: #059669; color: white; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; }
        .btn-tambah:hover { background-color: #047857; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 12px; text-align: left; font-size: 14px; }
        th { background-color: #f9fafb; color: #4b5563; }
        .btn-hapus { background-color: #dc2626; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 12px; font-weight: bold; }
        .btn-logout { background-color: #4b5563; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; float: right; font-size: 14px; font-weight: bold; cursor: pointer; border: none; }
        
        /* Gaya Khusus Smart Generator */
        .generator-box { background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px; margin-top: 8px; }
        .grid-generator { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px; }
        .btn-check-db { background: none; border: none; color: #2563eb; text-decoration: underline; font-size: 12px; font-weight: bold; cursor: pointer; padding: 0; text-align: right; display: block; width: 100%; margin-top: 2px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Tombol Log Out -->
        <form method="POST" action="/logout" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout">Keluar (Log Out)</button>
        </form>

        <h1>Preferences: Panel Admin Lab</h1>
        <p>Gunakan halaman ini untuk mengelola data barang yang tampil di katalog mahasiswa.</p>

        <div class="flex-container" style="display: flex; flex-direction: column; gap: 20px;">

                              <!-- FORM TAMBAH BARANG HORIZONTAL (SUDAH DISET KESAMPING) -->
            <div class="form-panel" style="flex: none; width: 100%; box-sizing: border-box; margin-bottom: 30px;">
                <h2>Tambah Barang</h2>
                <form action="/preferences/tambah" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    @csrf
                    
                    <!-- 1. NAMA BARANG -->
                    <div class="form-group" style="flex: 1; min-width: 180px; margin-bottom: 0;">
                        <label>Nama Barang</label>
                        <input type="text" name="name" placeholder="Contoh: LAPTOP" required style="padding: 10px;">
                    </div>

                    <!-- 2. MEREK -->
                    <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                        <label>Merek (Brand)</label>
                        <input type="text" name="brand" placeholder="Contoh: ASUS" required style="padding: 10px;">
                    </div>

                    <!-- 3. TIPE -->
                    <div class="form-group" style="flex: 1; min-width: 180px; margin-bottom: 0;">
                        <label>Tipe</label>
                        <input type="text" name="type" placeholder="Contoh: VIVOBOOK - A1404ZA" required style="padding: 10px;">
                    </div>
                    
                    <!-- 4. KUANTITAS & UNIT -->
                    <div class="form-group" style="width: 160px; margin-bottom: 0;">
                        <label>Kuantitas / Jumlah</label>
                        <div style="display: flex; gap: 5px;">
                            <input type="number" name="quantity" min="1" value="1" required style="flex: 1.5; padding: 10px;">
                            <select name="unit" style="flex: 1; padding: 10px;">
                                <option value="pcs">pcs</option>
                                <option value="ml">ml</option>
                            </select>
                        </div>
                    </div>

                    <!-- 5. SMART SERIAL GENERATOR COMPACT -->
                    <div class="form-group" style="width: 260px; margin-bottom: 0;">
                        <label>Konfigurasi Kode Serial</label>
                        <div class="generator-box" style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px; display: flex; flex-direction: column; gap: 6px;">
                            <div class="grid-generator" style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                                <div>
                                    <small style="font-weight: bold; color: #4b5563; font-size: 10px;">PREFIX</small>
                                    <select id="serial-prefix" name="prefix" onchange="updateSerialPreview()" style="padding: 4px; font-size: 11px; font-weight: bold; height: 28px;">
                                        <option value="B">B (BIOMEDIS)</option>
                                        <option value="I">I (INSTRUMENTASI)</option>
                                        <option value="K">K (KOMPONEN)</option>
                                        <option value="P">P (PC)</option>
                                        <option value="EK">EK (ELEKTRONIKA KANTOR)</option>
                                        <option value="M">M (MEKANIKAL)</option>
                                        <option value="C">C (CABEL)</option>
                                        <option value="A">A (ATK)</option>
                                    </select>
                                </div>
                                <div>
                                    <small style="font-weight: bold; color: #4b5563; font-size: 10px;">ABBR</small>
                                    <input type="text" id="serial-abbr" name="abbr" value="XXXX" oninput="this.value = this.value.toUpperCase(); updateSerialPreview();" maxlength="5" style="padding: 4px; font-size: 11px; font-weight: bold; text-align: center; height: 28px;">
                                </div>
                            </div>
                            <div class="grid-generator" style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                                <div>
                                    <small style="font-weight: bold; color: #4b5563; font-size: 10px;">YEAR</small>
                                    <input type="text" id="serial-year" value="{{ date('y') }}" readonly style="padding: 4px; font-size: 11px; text-align: center; background-color: #f3f4f6; height: 28px;">
                                </div>
                                <div>
                                    <small style="font-weight: bold; color: #4b5563; font-size: 10px;">START SEQ</small>
                                    <input type="text" id="serial-seq" value="001" oninput="updateSerialPreview()" style="padding: 4px; font-size: 11px; text-align: center; height: 28px;">
                                    <button type="button" class="btn-check-db" onclick="checkDatabaseSequence()" style="font-size: 10px; margin-top: 1px;">Check DB</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. HASIL SERIAL (INPUT HIDDEN ATAU PROPORSIONAL) -->
                    <div class="form-group" style="width: 160px; margin-bottom: 0;">
                        <label>Kode Serial Tergenerate</label>
                        <input type="text" id="final-serial-number" name="serial_code" readonly style="background-color: #eff6ff; color: #1e3a8a; font-family: monospace; font-weight: bold; border-color: #bfdbfe; padding: 10px; text-align: center;">
                    </div>

                    <!-- 7. STATUS -->
                    <div class="form-group" style="width: 140px; margin-bottom: 0;">
                        <label>Status</label>
                        <select name="status" style="padding: 10px;">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Sedang Dipinjam">Sedang Dipinjam</option>
                            <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                        </select>
                    </div>
                    
                    <!-- 8. TOMBOL AKSI DI KANAN BAWAH (UKURAN KECIL KONTRASTIF) -->
                    <button type="submit" class="btn-tambah" style="width: auto; padding: 11px 24px; white-space: nowrap; height: 40px; margin-bottom: 0; align-self: flex-end;">
                        Simpan Barang
                    </button>
                </form>
            </div>


            <!-- DAFTAR BARANG -->
            <div class="list-panel">
                <h2>Daftar Barang</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Merek</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Serial Number</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->brand }}</td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->quantity }} {{ $item->unit }}</td>
                            <td>{{ $item->serial_code ?? '-' }}</td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a href="/preferences/edit/{{ $item->id }}" style="background-color: #2563eb; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 12px; font-weight: bold;">Edit</a>
                                    <a href="/preferences/hapus/{{ $item->id }}" class="btn-hapus" onclick="return confirm('Hapus barang ini?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #6b7280;">
                                Belum ada data barang.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABEL LAPORAN AKTIVITAS PEMINJAMAN -->
        <div class="list-panel" style="flex: none; width: 100%; margin-top: 40px; box-sizing: border-box;">
            <h2>Laporan Aktivitas Peminjaman & Foto Kondisi</h2>
            <table>
                <thead>
                    <tr>
                        <th>Mahasiswa / NPM</th>
                        <th>Barang Dipinjam</th>
                        <th>Kondisi Awal (Foto)</th>
                        <th>Kondisi Kembali (Foto)</th>
                        <th>Status Log</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $loans = App\Models\Loan::with('item')->latest()->get();
                    @endphp

                    @forelse($loans as $l)
                    <tr>
                        <td>
                            <strong>{{ $l->student_name }}</strong><br>
                            {{ $l->npm }}
                        </td>
                        <td>
                            {{ $l->item->name ?? 'Barang Dihapus' }}<br>
                            <small>Jumlah: {{ $l->borrow_quantity }}</small>
                        </td>
                        <td>
                            {{ $l->borrow_condition }}<br>
                        </td>
                        <td>
                            {{ $l->return_condition ?? '-' }}
                            @if($l->return_photo)
                                <br>
                                <a href="/uploads/{{ $l->return_photo }}" target="_blank" style="color:#2563eb; font-size:12px;">Lihat Foto</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <strong>{{ $l->loan_status }}</strong>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #6b7280;">
                            Belum ada riwayat aktivitas peminjaman.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- LOGIKA JAVASCRIPT REKAYASA KODE SERIAL -->
    <script>
        function updateSerialPreview() {
            const prefix = document.getElementById('serial-prefix').value;
            let abbr = document.getElementById('serial-abbr').value.trim();
            const year = document.getElementById('serial-year').value;
            let seq = document.getElementById('serial-seq').value;

            if (abbr === "") {
                abbr = "XXXX";
            }

            if (seq !== "" && !isNaN(seq)) {
                seq = seq.toString().padStart(3, '0');
            }

            const finalSerial = `${prefix}-${abbr}-${year}${seq}`;
            document.getElementById('final-serial-number').value = finalSerial;
            document.getElementById('format-preview').innerText = `Format: ${prefix}-${abbr}-${year}00X`;
        }

        function checkDatabaseSequence() {
            const prefix = document.getElementById('serial-prefix').value;

            fetch(`/admin/inventaris/check-sequence?prefix=${encodeURIComponent(prefix)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Server error');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.next_seq) {
                        document.getElementById('serial-seq').value = data.next_seq;
                        updateSerialPreview();
                    }
                })
                .catch(err => {
                    alert('Gagal mengambil data dari database server.');
                    console.error(err);
                });
        }

        document.addEventListener("DOMContentLoaded", function() {
            updateSerialPreview();
        });
    </script>
</body>
</html>
