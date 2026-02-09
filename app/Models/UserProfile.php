<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType= "int";
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'phone_number',
        'gender',
        'dob',
        'bio',
        'parent_name',
        'parent_phone',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
