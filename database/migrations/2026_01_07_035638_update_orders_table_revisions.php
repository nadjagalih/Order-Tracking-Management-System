<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // 1. Ubah client_email menjadi nullable
            $table->string('client_email')->nullable()->change();
            
            // 2. Hapus kolom actual_completion
            $table->dropColumn('actual_completion');
        });
        
        // 3. Update enum status - hapus review dan revision, tambahkan revision_1 dan revision_2
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('draft', 'in_progress', 'revision_1', 'revision_2', 'completed', 'cancelled') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kembalikan client_email menjadi required
            $table->string('client_email')->nullable(false)->change();
            
            // Tambahkan kembali kolom actual_completion
            $table->datetime('actual_completion')->nullable();
        });
        
        // Kembalikan enum status ke versi lama
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('draft', 'in_progress', 'review', 'revision', 'completed', 'cancelled') DEFAULT 'draft'");
    }
};
