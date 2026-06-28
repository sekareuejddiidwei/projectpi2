<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $fillable = ['name', 'slug', 'formula'];

    /**
     * Get the rules for the material.
     */
    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class);
    }

    /**
     * Get the samples for the material.
     */
    public function samples(): HasMany
    {
        return $this->hasMany(Sample::class);
    }
}
