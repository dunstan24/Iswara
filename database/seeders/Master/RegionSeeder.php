<?php
namespace Database\Seeders\Master;
use Illuminate\Database\Seeder;
use App\Models\Master\Region;
use App\Enums\RegionType;

class RegionSeeder extends Seeder {
    public function run(): void {
        $regions = ['Banjar Adat A', 'Banjar Adat B', 'Banjar Dinas C'];
        foreach ($regions as $region) {
            Region::firstOrCreate(['region_name' => $region], ['region_type' => RegionType::Banjar]);
        }
    }
}