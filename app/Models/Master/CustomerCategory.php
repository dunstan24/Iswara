<?php
namespace App\Models\Master;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use App\Traits\HasAuditLog;
use App\Enums\ServiceLevel;
use App\Enums\CollectionFrequency;

#[Guarded(['category_id'])]
#[Table('master_customer_categories')]
class CustomerCategory extends Model {
    use HasAuditLog;
    protected $primaryKey = 'category_id';
    protected function casts(): array {
        return [
            'service_level' => ServiceLevel::class,
            'collection_frequency' => CollectionFrequency::class,
            'monthly_fee' => 'decimal:2',
        ];
    }
}