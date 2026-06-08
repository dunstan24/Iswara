<?php
namespace Database\Seeders\Master;
use Illuminate\Database\Seeder;
use App\Models\Master\PaymentMethod;
use App\Enums\PaymentCategory;

class PaymentMethodSeeder extends Seeder {
    public function run(): void {
        PaymentMethod::firstOrCreate(['payment_method_name' => 'Tunai Kolektor'], ['payment_category' => PaymentCategory::Kolektor]);
        PaymentMethod::firstOrCreate(['payment_method_name' => 'Transfer Bank BPD Bali'], ['payment_category' => PaymentCategory::Transfer, 'account_number' => '1234567890', 'account_name' => 'TPS 3R Sapuh Jagat']);
    }
}