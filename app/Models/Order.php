<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
    ];
    
    public $timestamps = false;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
