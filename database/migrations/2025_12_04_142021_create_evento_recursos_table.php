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
        Schema::create('evento_recursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')
              ->constrained('eventos')
              ->onDelete('cascade');

        $table->foreignId('recurso_id')
              ->constrained('recursos')
              ->onDelete('cascade');

        $table->integer('cantidad')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_recursos');
    }
};
