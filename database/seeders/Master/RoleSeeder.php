<?php
namespace Database\Seeders\Master;
use Illuminate\Database\Seeder;
use App\Models\Master\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder {
    public function run(): void {
        $permissions = [
            'view-complaints', 'create-complaints', 'edit-complaints', 'delete-complaints', 'assign-complaints', 'close-complaints',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $operatorRole = Role::firstOrCreate(['name' => 'operator']);
        $operatorRole->givePermissionTo(['view-complaints', 'create-complaints']);
    }
}