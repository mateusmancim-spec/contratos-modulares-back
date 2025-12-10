<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function clauses(): HasMany
    {
        return $this->hasMany(Clause::class);
    }
}
