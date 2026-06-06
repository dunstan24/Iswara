<?php
namespace App\Models\Master;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use App\Traits\HasAuditLog;
use App\Traits\HasPostGIS;
use App\Enums\RegionType;
use App\Enums\ServiceStatus;

#[Guarded(['region_id'])]
#[Table('master_regions')]
class Region extends Model {
    use HasAuditLog, HasPostGIS;
    protected $primaryKey = 'region_id';
    protected function casts(): array {
        return [
            'region_type' => RegionType::class,
            'service_status' => ServiceStatus::class,
        ];
    }

    public function roads()
    {
        return $this->hasMany(Road::class, 'region_id', 'region_id');
    }

    public function specialPlaces()
    {
        return $this->hasMany(SpecialPlace::class, 'region_id', 'region_id');
    }
}