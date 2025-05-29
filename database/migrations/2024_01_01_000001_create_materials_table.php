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
            $table->string('nome');
            $table->string('tipo');
            $table->decimal('preco_m2', 10, 2);
            $table->decimal('espessura_mm', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
