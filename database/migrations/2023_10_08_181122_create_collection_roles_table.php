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
        Schema::create('collection_roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->id();
            $table->string('slug', 255)
                ->comment('Identificador para el role, este es el slug que viene desde el cliente');
            $table->string('name', 255)
                ->comment('Human name readbly');
            $table->string('description', 511)
                ->nullable()
                ->comment('Descripción del role, sobre que trataría');

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
        Schema::dropIfExists('collection_roles');
    }
};
