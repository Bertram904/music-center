<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'type',
        'province','district', 'ward', 'street_address', 'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected function fullAddress(): \Attribute
    {
        return \Attribute::make(
          get: fn() => "{$this->street_address}, {$this->ward}, {$this->district}, {$this->province}"
        );
    }
}
