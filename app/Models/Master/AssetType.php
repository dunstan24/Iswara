<?php
namespace App\Models\Master;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use App\Traits\HasAuditLog;
use App\Enums\AssetCategory;
use App\Enums\AssetCriticality;

#[Guarded(['asset_type_id'])]
#[Table('master_asset_types')]
class AssetType extends Model {
    use HasAuditLog;
    protected $primaryKey = 'asset_type_id';
    protected function casts(): array {
        return [
            'asset_category' => AssetCategory::class,
            'asset_criticality' => AssetCriticality::class,
        ];
    }
}