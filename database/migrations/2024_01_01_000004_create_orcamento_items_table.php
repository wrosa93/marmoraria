<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcamentoItemsTable extends Migration
{
    public function up()
    {
        Schema::create('orcamento_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->foreignId('servico_id')->constrained()->onDelete('cascade');
            $table->text('descricao')->nullable();
            $table->decimal('quantidade_m2', 8, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orcamento_items');
    }
}
