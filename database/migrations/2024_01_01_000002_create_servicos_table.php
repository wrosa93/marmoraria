<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicosTable extends Migration
{
    public function up()
    {
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('nome', 120);
            $table->string('tipo_cobranca')->default('area');
            $table->string('unidade_medida')->default('m2');
            $table->boolean('ativo')->default(true);
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        Schema::create('servico_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servico_id')->constrained('servicos')->cascadeOnDelete();
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->decimal('preco', 10, 2);
            $table->string('moeda', 3)->default('BRL');
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->unique(['servico_id', 'data_inicio'], 'servico_price_inicio_unico');
            $table->index('data_inicio');
        });
    }

    public function down()
    {
        Schema::dropIfExists('servico_prices');
        Schema::dropIfExists('servicos');
    }
}
