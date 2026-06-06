<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasPostGIS
{
    /**
     * Set location from latitude and longitude coordinates.
     */
    public function setLocationFromCoordinates(float $lat, float $lng): void
    {
        $primaryKey = $this->getKeyName();
        $table = $this->getTable();

        DB::statement(
            "UPDATE {$table} 
             SET location = ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography 
             WHERE {$primaryKey} = ?",
            [$lng, $lat, $this->getKey()]
        );
    }

    /**
     * Scope to find records nearby a given point.
     */
    public function scopeNearby(Builder $query, float $lat, float $lng, int $radiusMeters = 500): Builder
    {
        return $query->whereRaw(
            "ST_DWithin(location::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, ?)",
            [$lng, $lat, $radiusMeters]
        )->selectRaw(
            "*, ST_Distance(location::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography) as distance",
            [$lng, $lat]
        )->orderBy('distance');
    }
}
