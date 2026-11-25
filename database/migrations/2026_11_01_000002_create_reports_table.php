<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $t) {
            $t->id();
            $t->string('provider_name');
            $t->string('title');
            $t->text('description')->nullable();
            $t->enum('category', ['layak', 'organik']);
            $t->boolean('urgent')->default(false);
            $t->boolean('rerouted')->default(false);
            $t->unsignedInteger('portions')->default(0);
            $t->decimal('weight_kg', 8, 1)->default(0);
            $t->dateTime('expires_at');
            $t->decimal('lat', 10, 7);
            $t->decimal('lng', 10, 7);
            $t->string('status')->default('waiting'); // waiting|claimed|on_the_way|delivered
            $t->foreignId('partner_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('provider_id')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('volunteer_name')->nullable();
            $t->string('photo_path')->nullable();
            $t->string('proof_path')->nullable();
            $t->dateTime('delivered_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
