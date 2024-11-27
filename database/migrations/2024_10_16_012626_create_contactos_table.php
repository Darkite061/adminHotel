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
        Schema::create('contacto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('correo', 255);
            $table->string('asunto', 255);
            $table->text('mensaje');
            $table->timestamp('fecha_contacto')->useCurrent();
            $table->boolean('atendida')->default(false)->after('mensaje'); // Nueva columna
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
