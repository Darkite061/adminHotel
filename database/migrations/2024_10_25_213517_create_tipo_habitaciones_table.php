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
        Schema::create('tipo_habitaciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 50);
            $table->text('descripcion');
            $table->string('imagen_url', 255);
            $table->decimal('precio', 10, 2);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_habitaciones');
    }
};
