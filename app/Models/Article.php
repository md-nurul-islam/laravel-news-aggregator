<?php

namespace App\Models;

use App\Observers\ArticleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([ArticleObserver::class])]
class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'source',
        'category',
        'author',
        'url',
        'image_url',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
