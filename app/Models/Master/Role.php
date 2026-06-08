<?php
namespace App\Models\Master;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Traits\HasAuditLog;

class Role extends SpatieRole {
    use HasAuditLog;
}