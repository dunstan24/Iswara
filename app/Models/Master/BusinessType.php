<?php
namespace App\Models\Master;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use App\Traits\HasAuditLog;
use App\Enums\ServiceCategory;
use App\Enums\CollectionFrequency;

#[Guarded(['business_type_id'])]
#[Table('master_business_types')]
class BusinessType extends Model {
    use HasAuditLog;
    protected $primaryKey = 'business_type_id';
    protected function casts(): array {
        return [
            'service_category' => ServiceCategory::class,
            'recommended_collection_frequency' => CollectionFrequency::class,
        ];
    }
}