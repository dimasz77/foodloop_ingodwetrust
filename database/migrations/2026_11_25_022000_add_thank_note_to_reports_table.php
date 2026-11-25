<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pesan terima kasih dari penerima kepada pemberi makanan saat serah terima selesai.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('thank_note', 200)->nullable()->after('proof_path');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('thank_note');
        });
    }
};
