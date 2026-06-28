<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parameter extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Get the rules that reference this parameter.
     */
    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class);
    }

    /**
     * Get the sample details that reference this parameter.
     */
    public function sampleDetails(): HasMany
    {
        return $this->hasMany(SampleDetail::class);
    }
}
