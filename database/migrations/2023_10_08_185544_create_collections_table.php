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
        Schema::create('collections', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->id();
            $table->unsignedBigInteger('collection_role_id')
                ->nullable()
                ->comment('Asocia con el role en la table de roles para colecciones generadas');

            $table->foreign('collection_role_id')
                ->references('id')->on('collection_roles')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');


            // Se mantiene temporalmente hasta terminar de utilizar la nueva tabla de roles.
            $table->string('role', 255)
                ->nullable()
                ->comment('Identifica el role usado para generar la imagen, parámetros que influyen en la descripción del prompt');


            $table->string('batch_id', 255)
                ->comment('Cadena para identificar el lote subido, esto se utiliza por que antes de subir no se conoce el ID');
            $table->string('ai')
                ->nullable()
                ->comment('Tipo de AI utilizada para generar imágenes. Puede usar distintos modelos (EJ: Stable Diffusion, Dall-e...');

            $table->string('title', 511)
                ->comment('Título que describe el contexto de las imágenes');
            $table->string('description', 1024)
                ->nullable()
                ->comment('Descripción sobre el contexto de las imágenes');
            $table->string('tags', 255)
                ->nullable()
                ->comment('Etiquetas de partes relevantes al contenido de las imágenes');
            $table->text('prompt')
                ->comment('Prompt utilizado en la AI para generar lote de imágenes');
            $table->text('negative_prompt')
                ->nullable()
                ->comment('Descripción de lo que no puede aparecer en la generación de la imagen');
            $table->string('model', 127)
                ->nullable()
                ->comment('Modelo utilizado para generar la imagen, si procediera');
            $table->string('size', 50)
                ->nullable()
                ->comment('Tamaño de la imagen al generarlas, no es el de salida');
            $table->string('size_resized', 50)
                ->nullable()
                ->comment('Tamaño de salida una vez generada la imagen.');
            $table->string('steps', 50)
                ->nullable()
                ->comment('Pasos para generar las imágenes');
            $table->string('cfg_scale', 10)
                ->nullable()
                ->comment('');
            $table->string('denoising_strength', 10)
                ->nullable()
                ->comment('');
            $table->boolean('restore_faces')
                ->default(false)
                ->comment('');
            $table->string('resizer', 127)
                ->nullable()
                ->comment('');
            $table->string('refiner_model', 127)
                ->nullable()
                ->comment('');
            $table->string('url_youtube', 255)
                ->nullable()
                ->comment('');

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
        Schema::dropIfExists('collections');
    }
};
