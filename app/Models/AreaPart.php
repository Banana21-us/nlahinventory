<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AreaPart extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function locationAreaParts(): HasMany
    {
        return $this->hasMany(LocationAreaPart::class, 'area_part_id', 'id');
    }
}
