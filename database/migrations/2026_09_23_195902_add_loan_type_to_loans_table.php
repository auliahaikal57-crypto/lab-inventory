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
    Schema::table('loans', function (Blueprint $table) {
        $table->enum('loan_type', ['Pribadi', 'Kelompok'])->default('Pribadi')->after('item_id');
        $table->integer('group_size')->default(1)->after('loan_type');
        // Mengubah tipe kolom student_name & npm menjadi text agar muat banyak nama kelompok
        $table->text('student_name')->change();
        $table->text('npm')->change();
    });
}

public function down(): void
{
    Schema::table('loans', function (Blueprint $table) {
        $table->dropColumn(['loan_type', 'group_size']);
    });
}
};
