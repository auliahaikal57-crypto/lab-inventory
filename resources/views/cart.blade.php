<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Peminjaman - Lab Biomedis STIE ITB</title>
    <style>
        body { background-color: #f3f4f6; font-family: Arial, sans-serif; margin: 0; padding: 40px; color: #333; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f3f4f6; padding-bottom: 15px; }
        .item-row { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e5e7eb; padding: 15px 0; }
        .item-info { display: flex; align-items: center; gap: 15px; }
        .item-img { width: 65px; height: 65px; object-fit: cover; border-radius: 8px; background: #eee; border: 1px solid #e5e7eb; }
        .btn-submit { background-color: #059669; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; }
        .btn-submit:hover { background-color: #047857; }
        .btn-danger { background-color: #dc2626; color: white; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 12px; }
        .btn-danger:hover { background-color: #b91c1c; }
        .alert-success { background-color: #d1fae5; color: #065f46; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-weight: bold; font-size: 14px; }
        .alert-error { background-color: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 22px; color: #1e293b;">🛒 Keranjang Peminjaman Alat</h1>
            <a href="/katalog" style="color: #2563eb; text-decoration: none; font-weight: bold; font-size: 14px;">← Kembali ke Katalog</a>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if(count($cart) > 0)
            <div style="margin-bottom: 20px;">
                @foreach($cart as $id => $details)
                    <div class="item-row">
                        <div class="item-info">
                            @if(!empty($details['foto']))
                                <img src="{{ asset('storage/' . $details['foto']) }}" class="item-img" alt="{{ $details['name'] }}">
                            @else
                                <div class="item-img" style="display:flex;align-items:center;justify-content:center;font-size:10px;color:#999;text-align:center;">Tidak ada foto</div>
                            @endif
                            <div>
                                <h3 style="margin: 0 0 4px 0; font-size: 16px; color: #1f2937;">{{ $details['name'] }}</h3>
                                <p style="margin: 0; font-size: 13px; color: #6b7280;">
                                    Merek: <strong>{{ $details['brand'] ?? '-' }}</strong> | Kode: <code>{{ $details['serial_code'] ?? '-' }}</code>
                                </p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: #2563eb; font-weight: bold;">
                                    Jumlah dipilih: {{ $details['quantity'] }} {{ $details['unit'] ?? 'pcs' }}
                                </p>
                            </div>
                        </div>
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Hapus</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center; border-top: 2px solid #f3f4f6; padding-top: 20px;">
                <span style="font-size: 14px; color: #6b7280;">Total Alat: <strong>{{ count($cart) }} Item</strong></span>
                <a href="{{ route('cart.checkout') }}" class="btn-submit">Lanjut ke Form Pengajuan Peminjaman →</a>
            </div>
        @else
            <div style="text-align: center; padding: 50px 0; color: #6b7280;">
                <p style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">Keranjang peminjaman Anda masih kosong.</p>
                <a href="/katalog" style="color: #2563eb; font-weight: bold; text-decoration: none;">+ Pilih Alat di Katalog Sekarang</a>
            </div>
        @endif
    </div>
</body>
</html>