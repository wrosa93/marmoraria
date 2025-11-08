<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcamentoItemsTable extends Migration
{
    public function up()
    {
        Schema::create('orcamento_locais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_id')->constrained('orcamentos')->cascadeOnDelete();
            $table->string('nome');
            $table->unsignedInteger('ordem')->default(1);
            $table->decimal('total_material', 10, 2)->default(0);
            $table->decimal('total_servico', 10, 2)->default(0);
            $table->decimal('total_custos_extras', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('orcamento_pecas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_local_id')->constrained('orcamento_locais')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->foreignId('material_price_id')->nullable()->constrained('material_prices')->nullOnDelete();
            $table->string('identificador')->nullable();
            $table->decimal('largura_mm', 8, 2);
            $table->decimal('comprimento_mm', 8, 2);
            $table->decimal('espessura_mm', 5, 2)->nullable();
            $table->unsignedInteger('quantidade')->default(1);
            $table->decimal('area_m2', 8, 3);
            $table->decimal('perimetro_ml', 8, 3)->default(0);
            $table->decimal('preco_material_unitario', 10, 2);
            $table->decimal('preco_material_total', 10, 2);
            $table->decimal('preco_servico_total', 10, 2)->default(0);
            $table->decimal('custos_extras', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('orcamento_peca_servicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_peca_id')->constrained('orcamento_pecas')->cascadeOnDelete();
            $table->foreignId('servico_id')->nullable()->constrained('servicos')->nullOnDelete();
            $table->foreignId('servico_price_id')->nullable()->constrained('servico_prices')->nullOnDelete();
            $table->string('descricao')->nullable();
            $table->string('tipo_cobranca')->nullable();
            $table->decimal('quantidade', 8, 3)->default(1);
            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orcamento_peca_servicos');
        Schema::dropIfExists('orcamento_pecas');
        Schema::dropIfExists('orcamento_locais');
    }
}
