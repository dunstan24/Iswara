<?php
namespace Database\Seeders\Master;
use Illuminate\Database\Seeder;
use App\Models\Master\WasteType;
use App\Enums\WasteCategory;
use App\Enums\ProcessingMethod;
use App\Enums\Unit;

class WasteTypeSeeder extends Seeder {
    public function run(): void {
        WasteType::firstOrCreate(['waste_name' => 'Sisa Makanan'], ['waste_category' => WasteCategory::Organik, 'processing_method' => ProcessingMethod::Kompos, 'unit' => Unit::Kg]);
        WasteType::firstOrCreate(['waste_name' => 'Botol Plastik PET'], ['waste_category' => WasteCategory::Anorganik, 'processing_method' => ProcessingMethod::BankSampah, 'unit' => Unit::Kg, 'default_price' => 2000]);
    }
}