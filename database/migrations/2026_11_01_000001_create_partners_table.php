<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('partners', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->enum('type', ['layak', 'organik']); // layak = penerima manfaat, organik = pengolah BSF/kompos
            $t->string('address')->nullable();
            $t->decimal('lat', 10, 7);
            $t->decimal('lng', 10, 7);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('partners'); }
};
