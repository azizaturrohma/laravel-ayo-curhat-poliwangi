<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            # Dashboard permissions
            'dashboard_access',
            # User permission
            'create_users', 'read_users', 'update_users', 'archive_users',
            # Reporting Permisson
            'create_reportings', 'read_reportings', 'read_all_reportings', 'show_detail_reportings', 'read__reporting_progress', 'create_reporting_progress', 'archive-reportings',
            # Emergency Call Permisson
            'emergency_call_access',
        ];

        $this->insertPermission($permissions);
    }

    private function insertPermission(array $permissions): void
    {
        $permissions = collect($permissions)->map(fn ($permission) => [
            'name' => $permission,
            'guard_name' => 'web',
            'created_at' => Carbon::now()
        ]);

        Permission::insert($permissions->toArray());
    }
}
