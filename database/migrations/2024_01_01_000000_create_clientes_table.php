<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('tipo_cliente')->default('pf');
            $table->string('documento')->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('telefone_secundario')->nullable();
            $table->string('endereco')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cep', 9)->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index('nome');
            $table->index('cidade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
