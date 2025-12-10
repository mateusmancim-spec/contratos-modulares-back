<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clause extends Model
{
    protected $fillable = [
        'contract_type_id',
        'title',
        'text_template',
        'is_mandatory',
        'order',
        'key',
    ];

    public function contractType(): BelongsTo
    {
        return $this->belongsTo(ContractType::class);
    }
}
