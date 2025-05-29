<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcamentosTable extends Migration
{
    public function up()
    {
        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->date('data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orcamentos');
    }
}
