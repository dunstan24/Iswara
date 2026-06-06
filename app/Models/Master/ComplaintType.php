<?php
namespace App\Models\Master;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use App\Traits\HasAuditLog;
use App\Enums\ComplaintCategory;
use App\Enums\PriorityLevel;
use App\Enums\ResponsibleRole;

#[Guarded(['complaint_type_id'])]
#[Table('master_complaint_types')]
class ComplaintType extends Model {
    use HasAuditLog;
    protected $primaryKey = 'complaint_type_id';
    protected function casts(): array {
        return [
            'complaint_category' => ComplaintCategory::class,
            'priority_level' => PriorityLevel::class,
            'responsible_role' => ResponsibleRole::class,
            'escalation_required' => 'boolean',
        ];
    }
}