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
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id('end_id');

            $table->foreignId('end_cid_id')
                ->constrained('cidades','cid_id')
                ->onDelete('cascade');
                
            $table->string('end_tipo_logradouro', 50)->index();
            $table->string('end_logradouro', 200)->index();
            $table->string('end_numero', 20)->index();
            $table->string('end_bairro', 100)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enderecos');
    }
};
