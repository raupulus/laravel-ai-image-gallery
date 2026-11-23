<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
            DB::statement('CREATE INDEX IF NOT EXISTS collections_title_trgm_idx ON collections USING gin (title gin_trgm_ops)');
            DB::statement('CREATE INDEX IF NOT EXISTS collections_tags_trgm_idx ON collections USING gin (tags gin_trgm_ops)');
            DB::statement("CREATE INDEX IF NOT EXISTS collections_active_created_id_idx ON collections (created_at DESC, id DESC) WHERE title IS NOT NULL AND title <> ''");
        }

        Schema::table('images', function (Blueprint $table): void {
            $table->index(['collection_id', 'order', 'id'], 'images_collection_id_order_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table): void {
            $table->dropIndex('images_collection_id_order_id_idx');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS collections_active_created_id_idx');
            DB::statement('DROP INDEX IF EXISTS collections_tags_trgm_idx');
            DB::statement('DROP INDEX IF EXISTS collections_title_trgm_idx');
        }
    }
};
