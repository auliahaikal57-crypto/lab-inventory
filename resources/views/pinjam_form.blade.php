<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Peminjaman Alat Lab</title>
    <style>
        body { background-color: #f3f4f6; font-family: Arial, sans-serif; padding: 40px; }
        .container { max-width: 550px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { background-color: #2563eb; color: white; border: none; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; margin-top: 15px; }
        .btn:hover { background-color: #1d4ed8; }
        .member-input-box { background-color: #f9fafb; padding: 15px; border: 1px solid #e5e7eb; border-radius: 6px; margin-bottom: 10px; }
        .member-title { font-weight: bold; color: #4b5563; margin-bottom: 8px; font-size: 13px; display: block; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Formulir Peminjaman Alat</h2>
        <p>Barang: <strong>{{ $item->name }}</strong> (Tersedia: {{ $item->quantity }} {{$item->unit }})</p>
        
        <form action="/katalog/pinjam/{{ $item->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- SELEKSI JENIS PEMINJAMAN -->
            <div class="form-group">
                <label>Jenis Peminjaman</label>
                <select name="loan_type" id="loan_type" onchange="toggleLoanType()">
                    <option value="Pribadi">Peminjaman Pribadi</option>
                    <option value="Kelompok">Peminjaman Kelompok</option>
                </select>
            </div>

            <!-- JUMLAH ORANG KELOMPOK -->
            <div class="form-group hidden" id="group_size_box">
                <label>Jumlah Anggota Kelompok</label>
                <select name="group_size" id="group_size" onchange="generateMemberFields()">
                    <option value="2">2 Orang</option>
                    <option value="3">3 Orang</option>
                    <option value="4">4 Orang</option>
                    <option value="5">5 Orang</option>
                </select>
            </div>

            <!-- TEMPAT INPUT NAMA & NPM DINAMIS -->
            <div id="dynamic_student_fields">
                <!-- Awal default peminjaman pribadi -->
                <div class="member-input-box">
                    <span class="member-title">Data Peminjam:</span>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="student_names[]" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>NPM / NIM</label>
                        <input type="text" name="npms[]" required>
                    </div>
                </div>
            </div>

            <!-- Bagian Form Lanjutan -->
            <div class="form-group">
                <label>Jumlah yang Dipinjam (Max: {{ $item->quantity }})</label>
                <input type="number" name="borrow_quantity" min="1" max="{{ $item->quantity }}" required>
            </div>
            <div class="form-group">
                <label>Kondisi Awal Barang</label>
                <textarea name="borrow_condition" placeholder="Contoh: Body mulus, siap pakai..." required></textarea>
            </div>
            <div class="form-group">
                <label>Foto Kondisi Awal</label>
                <input type="file" name="borrow_photo" accept="image/*" required>
            </div>
            
            <button type="submit" class="btn">Kirim Peminjaman</button>
        </form>
    </div>

    <!-- JAVASCRIPT INPUT OTOMATIS BERGANDA -->
    <script>
        function toggleLoanType() {
            const type = document.getElementById('loan_type').value;
            const groupSizeBox = document.getElementById('group_size_box');
            
            if (type === 'Kelompok') {
                groupSizeBox.classList.remove('hidden');
                generateMemberFields();
            } else {
                groupSizeBox.classList.add('hidden');
                document.getElementById('dynamic_student_fields').innerHTML = `
                    <div class="member-input-box">
                        <span class="member-title">Data Peminjam:</span>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="student_names[]" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>NPM / NIM</label>
                            <input type="text" name="npms[]" required>
                        </div>
                    </div>`;
            }
        }

        function generateMemberFields() {
            const size = parseInt(document.getElementById('group_size').value);
            const container = document.getElementById('dynamic_student_fields');
            container.innerHTML = '';

            for (let i = 1; i <= size; i++) {
                container.innerHTML += `
                    <div class="member-input-box">
                        <span class="member-title">Data Anggota Kelompok ke-${i}:</span>
                        <div class="form-group">
                            <label>Nama Lengkap Anggota ${i}</label>
                            <input type="text" name="student_names[]" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>NPM / NIM Anggota ${i}</label>
                            <input type="text" name="npms[]" required>
                        </div>
                    </div>`;
            }
        }
    </script>
</body>
</html>