<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_users_table_without_role_id_is_repaired_before_indexing(): void
    {
        $indexesMigration = require base_path('database/migrations/2026_11_23_000001_add_indexes_and_optimizations_to_gallery_tables.php');
        $repairMigration = require base_path('database/migrations/2026_11_23_000000_ensure_legacy_role_columns_exist.php');

        // Roll back the index migration and simulate an Oct 2023 users table missing role_id
        $indexesMigration->down();

        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        $this->assertFalse(Schema::hasColumn('users', 'role_id'));

        $repairMigration->up();
        $indexesMigration->up();

        $this->assertTrue(Schema::hasColumn('users', 'role_id'));
        $this->assertTrue(Schema::hasIndex('users', 'users_role_id_idx'));
    }
}
