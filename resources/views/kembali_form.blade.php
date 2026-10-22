<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pengembalian Alat</title>
    <style>
        body { background-color: #f3f4f6; font-family: Arial, sans-serif; padding: 40px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { background-color: #10b981; color: white; border: none; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Formulir Pengembalian</h2>
        <p>Mengembalikan: <strong>{{ $loan->item->name }}</strong></p>
        <p>Dipinjam oleh: {{ $loan->student_name }} ({{ $loan->borrow_quantity }} {{ $loan->item->unit }})</p>
        
        <form action="/katalog/kembali/{{ $loan->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Jumlah yang Dikembalikan</label>
                <input type="number" name="return_quantity" min="1" max="{{ $loan->borrow_quantity }}" required>
            </div>
            <div class="form-group">
                <label>Kondisi Barang Saat Dikembalikan</label>
                <textarea name="return_condition" placeholder="Contoh: Normal tidak ada cacat..." required></textarea>
            </div>
            <div class="form-group">
                <label>Foto Barang yang Dikembalikan</label>
                <input type="file" name="return_photo" accept="image/*" required>
            </div>
            <button type="submit" class="btn">Kirim Pengembalian</button>
        </form>
    </div>
</body>
</html>
