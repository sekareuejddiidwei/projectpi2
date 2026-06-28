<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rule extends Model
{
    protected $fillable = [
        'material_id',
        'parameter_id',
        'operator',
        'value',
    ];

    /**
     * Get the value as percentage (0-100).
     */
    public function getValueAttribute($value)
    {
        return $value * 100;
    }

    /**
     * Store the value as decimal (0-1).
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = $value / 100;
    }

    /**
     * Get the material that owns the rule.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Get the parameter that owns the rule.
     */
    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameter::class);
    }
}
