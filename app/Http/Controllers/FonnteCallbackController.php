<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class FonnteCallbackController extends Controller
{
    public function handleCallback(Request $request)
    {
        $sender  = $request->input('sender'); 
        $message = trim($request->input('message')); 

        Log::info("Fonnte Webhook Incoming: Sender={$sender}, Message={$message}");

        // 1. KEAMANAN: Cek apakah pengirim pesan benar-benar Nomor WA 2 (Admin)
        $adminNumber = env('ADMIN_WA_NUMBER');
        if ($sender !== $adminNumber) {
            Log::warning("Akses Ditolak: Pesan dari {$sender} bukan dari Admin.");
            return response()->json(['status' => 'ignored', 'reason' => 'Bukan Admin']);
        }

        // Format pesan: ACC 16 atau REJECT 16
        $words  = explode(' ', $message);
        $action = strtoupper($words[0] ?? '');
        $loanId = $words[1] ?? null;

        if (!$loanId || !is_numeric($loanId)) {
            return response()->json(['status' => 'ignored', 'reason' => 'Format tidak valid']);
        }

        // Tambahkan relasi 'user' agar nomor HP peminjam terbaca dari tabel users
        $loan = Loan::with(['item', 'user'])->find($loanId);

        if (!$loan) {
            WhatsAppService::sendMessage($sender, "❌ Gagal: Peminjaman ID #{$loanId} tidak ditemukan.");
            return response()->json(['status' => 'error', 'reason' => 'ID Peminjaman tidak ditemukan']);
        }

        // Cek jika peminjaman sudah pernah diproses sebelumnya
        if ($loan->loan_status !== 'Pending') {
            WhatsAppService::sendMessage($sender, "⚠️ Peminjaman #LOAN-{$loan->id} sudah diproses sebelumnya (Status: {$loan->loan_status}).");
            return response()->json(['status' => 'ignored', 'reason' => 'Sudah diproses']);
        }

        $itemName  = $loan->item->name ?? 'Barang'; 
        
        // Ambil nomor WA dan nama peminjam (Cek dari tabel loans dulu, jika kosong ambil dari relasi user)
        $userPhone = $loan->phone_number ?? $loan->user->phone_number ?? null;
        $userName  = $loan->borrower_name ?? $loan->user->name ?? 'Peminjam';

        // AKSI: ACC / APPROVE
        if ($action === 'ACC' || $action === 'APPROVE') {
            $loan->update(['loan_status' => 'Disetujui']);

            // 1. Notifikasi balik ke WA Admin/Teknisi
            WhatsAppService::sendMessage($sender, "✅ Sukses! Peminjaman #LOAN-{$loan->id} telah DISETUJUI.");

            // 2. Notifikasi otomatis ke WA Peminjam
            if (!empty($userPhone)) {
                $userMsg = "Halo {$userName}, peminjaman #LOAN-{$loan->id} untuk barang '{$itemName}' telah DISETUJUI. Silakan ambil barang di laboratorium.";
                WhatsAppService::sendMessage($userPhone, $userMsg);
            }

            return response()->json(['status' => 'success', 'message' => 'Loan Approved']);
        }

        // AKSI: REJECT / TOLAK
        if ($action === 'REJECT' || $action === 'TOLAK') {
            $loan->update(['loan_status' => 'Ditolak']);

            // 1. Notifikasi balik ke WA Admin/Teknisi
            WhatsAppService::sendMessage($sender, "❌ Peminjaman #LOAN-{$loan->id} telah DITOLAK.");

            // 2. Notifikasi otomatis ke WA Peminjam
            if (!empty($userPhone)) {
                $userMsg = "Halo {$userName}, mohon maaf peminjaman #LOAN-{$loan->id} untuk barang '{$itemName}' DITOLAK oleh Admin.";
                WhatsAppService::sendMessage($userPhone, $userMsg);
            }

            return response()->json(['status' => 'success', 'message' => 'Loan Rejected']);
        }

        return response()->json(['status' => 'ignored']);
    }
}