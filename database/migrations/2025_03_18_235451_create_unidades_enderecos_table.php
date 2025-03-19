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
        Schema::create('unidades_enderecos', function (Blueprint $table) {
            
            $table->foreignId('unid_id')
                ->constrained('unidades','unid_id')
                ->onDelete('cascade');

            $table->foreignId('end_id')
                ->constrained('enderecos','end_id')
                ->onDelete('cascade');
    
            // Definir a chave primária composta
            $table->primary(['unid_id', 'end_id']);
    
            $table->timestamps();
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades_enderecos');
    }
};
