<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword_id',
        'post_id',
    ];

    protected $table = "posts_keywords";
}
