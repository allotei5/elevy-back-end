<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAuthor extends Model
{
    use HasFactory;

    protected $fillable = [
        'post',
        'author'
    ];

    protected $table = "authors_posts";
}
