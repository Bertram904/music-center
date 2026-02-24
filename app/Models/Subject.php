<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;
    protected $fillable = [
        'name', 'code',
        'slug', 'description',
        'is_active'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];
}
