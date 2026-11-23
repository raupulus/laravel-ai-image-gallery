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
        Schema::table('collections', function (Blueprint $table): void {
            $table->comment('Colecciones de imágenes generadas por IA y sus parámetros de generación');
            $table->string('cfg_scale', 50)->nullable()->change();
            $table->string('denoising_strength', 50)->nullable()->change();
            $table->unique('batch_id', 'collections_batch_id_unique');
            $table->index('created_at', 'collections_created_at_idx');
            $table->index('collection_role_id', 'collections_collection_role_id_idx');
        });

        Schema::table('images', function (Blueprint $table): void {
            $table->comment('Imágenes individuales pertenecientes a cada colección en formato WebP');
            $table->index(['collection_id', 'order'], 'images_collection_id_order_idx');
        });

        Schema::table('roles', function (Blueprint $table): void {
            $table->comment('Roles de acceso y administración de usuarios');
            $table->unique('slug', 'roles_slug_unique');
        });

        Schema::table('collection_roles', function (Blueprint $table): void {
            $table->comment('Estilos y perfiles temáticos aplicados al generar colecciones');
            $table->unique('slug', 'collection_roles_slug_unique');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->index('role_id', 'users_role_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex('users_role_id_idx');
        });

        Schema::table('collection_roles', function (Blueprint $table): void {
            $table->dropUnique('collection_roles_slug_unique');
        });

        Schema::table('roles', function (Blueprint $table): void {
            $table->dropUnique('roles_slug_unique');
        });

        Schema::table('images', function (Blueprint $table): void {
            $table->dropIndex('images_collection_id_order_idx');
        });

        Schema::table('collections', function (Blueprint $table): void {
            $table->dropIndex('collections_collection_role_id_idx');
            $table->dropIndex('collections_created_at_idx');
            $table->dropUnique('collections_batch_id_unique');
            $table->string('cfg_scale', 10)->nullable()->change();
            $table->string('denoising_strength', 10)->nullable()->change();
        });
    }
};
