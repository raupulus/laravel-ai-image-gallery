<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->id();
            $table->unsignedBigInteger('collection_id')
                ->comment('');
            $table->foreign('collection_id')
                ->references('id')->on('collections')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->integer('order')
                ->nullable()
                ->comment('Orden de aparición para las imágenes');
            $table->bigInteger('seed')
                ->nullable()
                ->comment('Semilla identificando patrón para generar imágenes similares');
            $table->string('image', 511)
                ->nullable()
                ->comment('Ruta hacia la imagen principal en la máxima resolución');
            $table->string('thumbnail', 511)
                ->nullable()
                ->comment('Ruta hacia la imagen de las miniaturas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
        });
    }
};
