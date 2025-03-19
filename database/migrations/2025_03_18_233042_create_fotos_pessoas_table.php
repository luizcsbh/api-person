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
        Schema::create('fotos_pessoas', function (Blueprint $table) {
            $table->id('ft_id');
            
            $table->foreignId('pes_id')
                ->constrained('pessoas','pes_id')
                ->onDelete('cascade');
            
            // Demais colunas
            $table->date('ft_data')->index();
            $table->string('ft_bucket', 50);
            $table->string('ft_hash', 64)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotos_pessoas');
    }
};
