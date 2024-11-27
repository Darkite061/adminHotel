<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Crear la tabla 'blocks'
        Schema::create('block', function (Blueprint $table) {
            $table->id();
            $table->string('previous_hash');
            $table->string('data');
            $table->timestamp('timestamp');
            $table->string('hash');
            $table->timestamps();
        });

        // Insertar el Genesis Block
        BlockchainBlock::create([
            'previous_hash' => '0',  // No tiene un bloque anterior
            'data' => 'Genesis Block',  // Mensaje o datos del bloque
            'timestamp' => now(),  // Marca de tiempo actual
            'hash' => hash('sha256', 'Genesis Block' . now()),  // Hash generado
        ]);
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block');
    }
};

