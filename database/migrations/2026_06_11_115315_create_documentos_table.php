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
        Schema::create('documentos', function (Blueprint $table) {

            $table->id();

            $table->string('titulo');

            $table->text('descricao')
                ->nullable();

            $table->string('arquivo');

            $table->string('setor')
                ->nullable();

            $table->string('crf')
                ->nullable();

            $table->enum('tipo_pessoa', [
                'PF',
                'PJ'
            ])->nullable();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
