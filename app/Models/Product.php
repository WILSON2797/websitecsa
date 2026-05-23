<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag',
        'name',
        'desc',
        'specs',
        'icon',
        'img',
        'tolerance',
        'capacity',
        'speed',
        'volume',
        'auxiliary',
        'safety',
        'typical',
    ];

    protected $casts = [
        'specs' => 'array',
    ];
}
