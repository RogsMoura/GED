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
        Schema::create('ged_index', function (Blueprint $table) {
            $table->id();

            $table->string('tipo'); // pf, pj, setores

            $table->text('path');   // caminho completo relativo
            $table->string('nome');

            $table->boolean('is_file')->default(false);

            $table->text('parent_path')->nullable();

            $table->timestamps();

            $table->index(['tipo', 'parent_path']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ged_index');
    }
};
