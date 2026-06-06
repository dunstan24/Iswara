<?php
namespace Database\Seeders\Master;
use Illuminate\Database\Seeder;
use App\Models\Master\CustomerCategory;
use App\Enums\ServiceLevel;
use App\Enums\CollectionFrequency;

class CustomerCategorySeeder extends Seeder {
    public function run(): void {
        CustomerCategory::firstOrCreate(['category_name' => 'Rumah Tangga Biasa'], ['service_level' => ServiceLevel::Standar, 'collection_frequency' => CollectionFrequency::DuaHariSekali, 'monthly_fee' => 20000]);
        CustomerCategory::firstOrCreate(['category_name' => 'Usaha Mikro'], ['service_level' => ServiceLevel::Standar, 'collection_frequency' => CollectionFrequency::Harian, 'monthly_fee' => 50000]);
    }
}