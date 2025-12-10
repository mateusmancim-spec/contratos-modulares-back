<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clauses', function (Blueprint $table) {
            $table->id();

            // Relaciona com o tipo de contrato
            $table->foreignId('contract_type_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('title');        // "CLÁUSULA 1ª - DO OBJETO"
            $table->text('text_template');  // Texto com {{PLACEHOLDERS}}
            $table->boolean('is_mandatory')->default(false); // se é obrigatória
            $table->integer('order')->default(0);            // ordem no contrato
            $table->string('key')->nullable();               // ex: "MULTA_RESCISORIA"

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clauses');
    }
};
