<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Terhubung ke tabel items
        $table->string('student_name');
        $table->string('npm');
        $table->integer('borrow_quantity');
        $table->string('borrow_condition');
        $table->string('borrow_photo'); // Menyimpan nama file foto awal
        
        // Data Pengembalian (Boleh kosong dulu saat awal meminjam)
        $table->integer('return_quantity')->nullable();
        $table->string('return_condition')->nullable();
        $table->string('return_photo')->nullable(); // Menyimpan nama file foto akhir
        $table->enum('loan_status', ['Aktif', 'Selesai'])->default('Aktif');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
