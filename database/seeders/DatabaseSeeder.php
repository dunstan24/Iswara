<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Master\ComplaintTypeSeeder;
use Database\Seeders\Master\CustomerCategorySeeder;
use Database\Seeders\Master\PaymentMethodSeeder;
use Database\Seeders\Master\RegionSeeder;
use Database\Seeders\Master\RoleSeeder;
use Database\Seeders\Master\UserSeeder;
use Database\Seeders\Master\WasteTypeSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            ComplaintTypeSeeder::class,
            CustomerCategorySeeder::class,
            PaymentMethodSeeder::class,
            RegionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            WasteTypeSeeder::class,
        ]);
    }
}
