<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Admin Lab</title>
    <style>
        body { background-color: #f3f4f6; font-family: Arial, sans-serif; margin: 0; padding: 40px; color: #333; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { color: #1f2937; font-size: 24px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .flex-group { display: flex; gap: 10px; }
        .btn-simpan { background-color: #2563eb; color: white; border: none; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; margin-top: 10px; }
        .btn-simpan:hover { background-color: #1d4ed8; }
        .btn-batal { display: block; text-align: center; color: #4b5563; text-decoration: none; margin-top: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Data Barang</h1>
        
        <form action="/preferences/edit/{{ $item->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="name" value="{{ $item->name }}" required>
            </div>
            <div class="form-group">
                <label>Merek (Brand)</label>
                <input type="text" name="brand" value="{{ $item->brand }}" required>
            </div>
            <div class="form-group">
                <label>Tipe</label>
                <input type="text" name="type" value="{{ $item->type }}" required>
            </div>
            <div class="form-group">
                <label>Kuantitas / Jumlah</label>
                <div class="flex-group">
                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="flex: 2;" required>
                    <select name="unit" style="flex: 1;">
                        <option value="pcs" {{ $item->unit == 'pcs' ? 'selected' : '' }}>pcs</option>
                        <option value="ml" {{ $item->unit == 'ml' ? 'selected' : '' }}>ml</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Kode Serial</label>
                <input type="text" name="serial_code" value="{{ $item->serial_code }}" required>
            </div>
            <div class="form-group">
                <label>Foto Barang (Maks. 2MB)</label>
                @if($item->foto)
                    <div style="margin-bottom: 8px;">
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Barang" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc;">
                    </div>
                @endif
                <input type="file" name="foto" accept="image/*">
            </div>
            <div class="form-group">
                <label>Ketersediaan (Status)</label>
                <select name="status">
                    <option value="Tersedia" {{ $item->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Sedang Dipinjam" {{ $item->status == 'Sedang Dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="Dalam Perbaikan" {{ $item->status == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                </select>
            </div>
            <button type="submit" class="btn-simpan">Perbarui Data</button>
            <a href="/preferences" class="btn-batal">Batal</a>
        </form>
    </div>
</body>
</html>