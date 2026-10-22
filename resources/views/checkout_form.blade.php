<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Peminjaman - Lab Biomedis STEI ITB</title>
    <style>
        body { background-color: #f3f4f6; font-family: Arial, sans-serif; margin: 0; padding: 40px; color: #333; }
        .container { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { color: #1f2937; font-size: 22px; margin-bottom: 20px; border-bottom: 2px solid #f3f4f6; padding-bottom: 12px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px; color: #374151; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-size: 14px; }
        .btn-submit { background-color: #2563eb; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; margin-top: 15px; font-size: 15px; }
        .btn-submit:hover { background-color: #1d4ed8; }
        
        .cart-summary { background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        .cart-item-badge { display: inline-block; background: #e0e7ff; color: #3730a3; padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; margin: 3px; }

        .member-card { background: #f9fafb; border: 1px solid #e5e7eb; padding: 15px; border-radius: 8px; margin-bottom: 10px; position: relative; }
        .btn-add-member { background-color: #059669; color: white; border: none; padding: 8px 14px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 13px; margin-top: 5px; }
        .btn-remove-member { background-color: #ef4444; color: white; border: none; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 12px; float: right; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 8px; }
        .hidden-panel { display: none !important; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Form Pengajuan Peminjaman Alat</h1>

        <!-- Rangkuman Alat dari Keranjang -->
        <div class="cart-summary">
    <label style="margin-bottom: 8px; font-size: 13px; color: #64748b;">Daftar Alat di Keranjang:</label>
    <div>
        @foreach($cart as $item)
            <span class="cart-item-badge">
                📦 {{ $item['name'] }} ({{ $item['quantity'] }} {{ $item['unit'] ?? 'pcs' }})
            </span>
        @endforeach
    </div>
</div>

        <form action="{{ route('cart.processCheckout') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 1. Pilihan Kategori Peminjaman -->
            <div class="form-group">
                <label>Jenis Peminjaman</label>
                <select name="loan_type" id="loan_type" onchange="toggleLoanType()">
                    <option value="Pribadi">Pribadi / Mandiri (Tugas Akhir/Individu)</option>
                    <option value="Kelompok">Kelompok / Tim Praktikum</option>
                </select>
            </div>

            <!-- DATA PEMOHON UTAMA / KETUA -->
            <div class="member-card" style="background: #eff6ff; border-color: #bfdbfe;">
                <label style="color: #1e40af; margin-bottom: 10px;" id="label-ketua">Data Pemohon Utama / Ketua Kelompok</label>
                <div class="grid-2">
                    <div>
                        <label style="font-size: 12px;">Nama Lengkap</label>
                        <input type="text" name="student_names[]" value="{{ Auth::user()->name }}" required readonly style="background: #e2e8f0;">
                    </div>
                    <div>
                        <label style="font-size: 12px;">NIM / NIP</label>
                        <input type="text" name="npms[]" placeholder="Masukkan NIM Anda..." required>
                    </div>
                </div>
            </div>

            <!-- ANGGOTA KELOMPOK (DINAMIS JAVASCRIPT) -->
            <div id="section-kelompok" class="hidden-panel">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <label style="margin: 0; color: #0f172a;">Daftar Anggota Kelompok</label>
                    <button type="button" class="btn-add-member" onclick="addMemberRow()">+ Tambah Anggota</button>
                </div>
                <div id="members-container">
                    <!-- Row Anggota Tambahan Muncung via JS -->
                </div>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label>Kondisi Awal Alat Saat Pengambilan</label>
                <input type="text" name="borrow_condition" placeholder="Contoh: Baik, Fisik Mulus & Lengkap" required>
            </div>

            <div class="form-group">
                <label>Foto Kondisi Awal Saat Pengambilan (Maks 2MB)</label>
                <input type="file" name="borrow_photo" accept="image/*" required>
            </div>

            <button type="submit" class="btn-submit">Kirim Pengajuan Peminjaman</button>
            <a href="{{ route('cart.index') }}" style="display: block; text-align: center; color: #64748b; text-decoration: none; margin-top: 15px; font-size: 14px;">Batal & Kembali ke Keranjang</a>
        </form>
    </div>

    <!-- JAVASCRIPT INPUT ANGGOTA DINAMIS -->
    <script>
        let memberCount = 0;

        function toggleLoanType() {
            const loanType = document.getElementById('loan_type').value;
            const sectionKelompok = document.getElementById('section-kelompok');
            const labelKetua = document.getElementById('label-ketua');

            if (loanType === 'Kelompok') {
                sectionKelompok.classList.remove('hidden-panel');
                labelKetua.innerText = "Data Ketua Kelompok (Pemohon Utama)";
                if (memberCount === 0) {
                    addMemberRow();
                }
            } else {
                sectionKelompok.classList.add('hidden-panel');
                labelKetua.innerText = "Data Pemohon Utama";
            }
        }

        function addMemberRow() {
            memberCount++;
            const container = document.getElementById('members-container');
            const memberDiv = document.createElement('div');
            memberDiv.className = 'member-card';
            memberDiv.id = `member-row-${memberCount}`;

            memberDiv.innerHTML = `
                <button type="button" class="btn-remove-member" onclick="removeMemberRow(${memberCount})">Hapus</button>
                <label style="font-size: 13px; color: #475569;">Anggota Tim #${memberCount}</label>
                <div class="grid-2">
                    <div>
                        <input type="text" name="student_names[]" placeholder="Nama Lengkap Anggota" required>
                    </div>
                    <div>
                        <input type="text" name="npms[]" placeholder="NIM / NIP Anggota" required>
                    </div>
                </div>
            `;
            container.appendChild(memberDiv);
        }

        function removeMemberRow(id) {
            const row = document.getElementById(`member-row-${id}`);
            if (row) {
                row.remove();
            }
        }
    </script>
</body>
</html>