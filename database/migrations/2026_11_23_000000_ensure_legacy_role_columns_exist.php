<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table): void {
                $table->id();
                $table->string('slug');
                $table->string('name');
                $table->string('show_name');
                $table->string('description', 255);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->unsignedBigInteger('role_id')
                    ->nullable()
                    ->comment('Role que representa al usuario');

                $table->foreign('role_id', 'users_role_id_foreign')
                    ->references('id')->on('roles')
                    ->onUpdate('CASCADE')
                    ->onDelete('SET NULL');
            });
        }

        if (! Schema::hasTable('collection_roles')) {
            Schema::create('collection_roles', function (Blueprint $table): void {
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

        if (Schema::hasTable('collections') && ! Schema::hasColumn('collections', 'collection_role_id')) {
            Schema::table('collections', function (Blueprint $table): void {
                $table->unsignedBigInteger('collection_role_id')
                    ->nullable()
                    ->comment('Asocia con el role en la table de roles para colecciones generadas');

                $table->foreign('collection_role_id', 'collections_collection_role_id_foreign')
                    ->references('id')->on('collection_roles')
                    ->onUpdate('CASCADE')
                    ->onDelete('SET NULL');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Repair migration: only adds missing columns on legacy schemas where initial create migrations lacked them.
    }
};
