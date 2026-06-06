<?php
namespace App\Models\Master;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use App\Traits\HasAuditLog;
use App\Enums\WasteCategory;
use App\Enums\ProcessingMethod;
use App\Enums\Unit;

#[Guarded(['waste_type_id'])]
#[Table('master_waste_types')]
class WasteType extends Model {
    use HasAuditLog;
    protected $primaryKey = 'waste_type_id';
    protected function casts(): array {
        return [
            'waste_category' => WasteCategory::class,
            'processing_method' => ProcessingMethod::class,
            'unit' => Unit::class,
            'default_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}