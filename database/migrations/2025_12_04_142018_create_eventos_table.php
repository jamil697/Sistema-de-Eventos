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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
              $table->string('titulo');
            $table->string('tipo'); 
            $table->date('fechaInicio');
            $table->date('fechaFin');
            $table->string('ubicacion');
            $table->integer('capacidad');
            $table->boolean('esDePago')->default(false);
            $table->string('estado')->default('activo'); 
            $table->decimal('costo', 8, 2)->nullable(); 

            $table->foreignId('administrador_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
