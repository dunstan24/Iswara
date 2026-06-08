<?php
namespace App\Models\Master;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use App\Traits\HasAuditLog;
use App\Traits\HasPostGIS;
use App\Enums\RoadType;
use App\Enums\AccessType;
use App\Enums\RoadSurface;
use App\Enums\RoadCondition;
use App\Enums\ServiceStatus;

#[Guarded(['road_id'])]
#[Table('master_roads')]
class Road extends Model {
    use HasAuditLog, HasPostGIS;
    protected $primaryKey = 'road_id';
    protected function casts(): array {
        return [
            'road_type' => RoadType::class,
            'access_type' => AccessType::class,
            'road_surface' => RoadSurface::class,
            'road_condition' => RoadCondition::class,
            'service_status' => ServiceStatus::class,
        ];
    }
    public function region() { return $this->belongsTo(Region::class, 'region_id', 'region_id'); }
}