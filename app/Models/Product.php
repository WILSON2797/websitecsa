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
        'detail_img1',
        'detail_img2',
        'detail_img3',
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
