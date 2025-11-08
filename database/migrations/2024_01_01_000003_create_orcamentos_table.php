<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcamentosTable extends Migration
{
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('nome', 120);
            $table->boolean('permite_parcelamento')->default(false);
            $table->unsignedInteger('max_parcelas')->default(1);
            $table->decimal('taxa_percentual', 5, 2)->default(0);
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 25)->unique();
            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->date('data')->index();
            $table->date('data_validade')->nullable();
            $table->string('status')->default('rascunho');
            $table->enum('desconto_tipo', ['nenhum', 'percentual', 'valor'])->default('nenhum');
            $table->decimal('desconto_percentual', 5, 2)->default(0);
            $table->decimal('desconto_valor', 10, 2)->default(0);
            $table->decimal('acrescimo_valor', 10, 2)->default(0);
            $table->decimal('total_material', 10, 2)->default(0);
            $table->decimal('total_servico', 10, 2)->default(0);
            $table->decimal('total_custos_extras', 10, 2)->default(0);
            $table->decimal('total_bruto', 10, 2)->default(0);
            $table->decimal('total_desconto', 10, 2)->default(0);
            $table->decimal('total_acrescimo', 10, 2)->default(0);
            $table->decimal('total_liquido', 10, 2)->default(0);
            $table->date('data_base_precos')->nullable();
            $table->string('responsavel')->nullable();
            $table->text('condicoes_pagamento')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orcamentos');
        Schema::dropIfExists('payment_methods');
    }
}
