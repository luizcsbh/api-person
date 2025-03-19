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
        Schema::create('pessoas_enderecos', function (Blueprint $table) {

            $table->foreignId('pes_id')
                ->constrained('pessoas','pes_id') 
                ->onDelete('cascade');
            
            $table->foreignId('end_id')
                ->constrained('enderecos','end_id') 
                ->onDelete('cascade');
            
            $table->primary(['pes_id', 'end_id']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoas_enderecos');
    }
};
