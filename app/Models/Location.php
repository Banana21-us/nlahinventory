<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'floor'];

    public function locationAreaParts(): HasMany
    {
        return $this->hasMany(LocationAreaPart::class, 'location_id', 'id');
    }

    public function isCR(): bool
    {
        return str_contains($this->name, '| CR')
            || str_ends_with(trim($this->name), 'CR');
    }
}
