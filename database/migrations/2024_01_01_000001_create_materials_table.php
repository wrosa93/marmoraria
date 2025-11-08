<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('nome', 120);
            $table->string('tipo')->nullable();
            $table->string('acabamento')->nullable();
            $table->string('cor')->nullable();
            $table->decimal('espessura_padrao_mm', 5, 2)->nullable();
            $table->boolean('ativo')->default(true);
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        Schema::create('material_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->decimal('preco_m2', 10, 2);
            $table->string('moeda', 3)->default('BRL');
            $table->boolean('ativo')->default(true);
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->unique(['material_id', 'data_inicio'], 'material_price_inicio_unico');
            $table->index('data_inicio');
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_prices');
        Schema::dropIfExists('materials');
    }
}
