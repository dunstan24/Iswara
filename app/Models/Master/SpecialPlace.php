<?php
namespace App\Models\Master;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use App\Traits\HasAuditLog;
use App\Traits\HasPostGIS;
use App\Enums\PlaceType;
use App\Enums\ServiceCategory;
use App\Enums\ServiceStatus;

#[Guarded(['place_id'])]
#[Table('master_special_places')]
class SpecialPlace extends Model {
    use HasAuditLog, HasPostGIS;
    protected $primaryKey = 'place_id';
    protected function casts(): array {
        return [
            'place_type' => PlaceType::class,
            'service_category' => ServiceCategory::class,
            'service_status' => ServiceStatus::class,
        ];
    }
    public function region() { return $this->belongsTo(Region::class, 'region_id', 'region_id'); }
}