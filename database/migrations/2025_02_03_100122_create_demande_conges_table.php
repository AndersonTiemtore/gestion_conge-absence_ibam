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
        Schema::create('demande_conges', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->date('date_soumission');
            $table->text('motif');
            $table->enum('type_conge', ['compensation', 'conge_payé', 'maternité', 'paternité', 'ancienneté', 'maladie', 'autre']);

            $table->foreignUuid('employe_id')->constrained('employes', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('statut_demande_id')->constrained('statut_demandes', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_conges');
    }
};
