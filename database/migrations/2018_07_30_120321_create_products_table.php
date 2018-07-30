<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->longText('description');
            $table->bigInteger('amount');
            $table->bigInteger('price');
            $table->bigInteger('promotion');
            $table->boolean('status_promotion')->comment("Se o produto esta ou nao em promocao");
            $table->bigInteger('weight')->comment("Peso produto");
            $table->bigInteger('dimension')->comment("Dimensoes do produto, referente ao tamanho");
                    
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
