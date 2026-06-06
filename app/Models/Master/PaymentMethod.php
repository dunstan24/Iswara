<?php
namespace App\Models\Master;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use App\Traits\HasAuditLog;
use App\Enums\PaymentCategory;

#[Guarded(['payment_method_id'])]
#[Table('master_payment_methods')]
class PaymentMethod extends Model {
    use HasAuditLog;
    protected $primaryKey = 'payment_method_id';
    protected function casts(): array {
        return [
            'payment_category' => PaymentCategory::class,
            'is_active' => 'boolean',
        ];
    }
}