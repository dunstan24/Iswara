<?php
namespace Database\Seeders\Master;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\AccountStatus;

class UserSeeder extends Seeder {
    public function run(): void {
        $admin = User::firstOrCreate(
            ['email' => 'admin@iswara.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('password'), 'account_status' => AccountStatus::Aktif]
        );
        if (!$admin->hasRole('admin')) $admin->assignRole('admin');
        
        $operator = User::firstOrCreate(
            ['email' => 'operator@iswara.com'],
            ['name' => 'Operator TPS3R', 'password' => bcrypt('password'), 'account_status' => AccountStatus::Aktif]
        );
        if (!$operator->hasRole('operator')) $operator->assignRole('operator');
    }
}