<?php
namespace Database\Seeders\Master;
use Illuminate\Database\Seeder;
use App\Models\Master\ComplaintType;
use App\Enums\ComplaintCategory;
use App\Enums\PriorityLevel;
use App\Enums\ResponsibleRole;

class ComplaintTypeSeeder extends Seeder {
    public function run(): void {
        ComplaintType::firstOrCreate(['complaint_name' => 'Sampah Tidak Diambil'], ['complaint_category' => ComplaintCategory::Pengangkutan, 'priority_level' => PriorityLevel::Tinggi, 'responsible_role' => ResponsibleRole::Operator]);
        ComplaintType::firstOrCreate(['complaint_name' => 'Iuran Tidak Sesuai'], ['complaint_category' => ComplaintCategory::Keuangan, 'priority_level' => PriorityLevel::Sedang, 'responsible_role' => ResponsibleRole::Admin]);
    }
}