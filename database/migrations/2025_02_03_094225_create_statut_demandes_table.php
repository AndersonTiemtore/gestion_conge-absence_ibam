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
        Schema::create('statut_demandes', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->enum('statut', ['plannifier', 'demander', 'refuser', 'accepter']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statut_demandes');
    }
};
