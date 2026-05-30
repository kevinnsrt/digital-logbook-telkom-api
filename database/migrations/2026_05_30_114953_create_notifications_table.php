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
        Schema::create('notifications', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('message');
        $table->string('status_type'); // untuk menandai ini notif 'add', 'pending', 'approved', atau 'taken'
        $table->unsignedBigInteger('user_id')->nullable(); // Siapa yang melakukan aksi
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
