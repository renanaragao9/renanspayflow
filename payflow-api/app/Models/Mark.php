<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Mark extends BaseModel
{
    protected $table = 'marks';

    protected $fillable = [
        'name',
        'description',
        'country',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}