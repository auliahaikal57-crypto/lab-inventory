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
    Schema::table('items', function (Blueprint $table) {
        $table->integer('quantity')->default(1)->after('type');
        $table->enum('unit', ['pcs', 'ml'])->default('pcs')->after('quantity');
    });
}

public function down(): void
{
    Schema::table('items', function (Blueprint $table) {
        $table->dropColumn(['quantity', 'unit']);
    });
}

};
