<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'author',
        'title',
        'body',
        'cover',
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
