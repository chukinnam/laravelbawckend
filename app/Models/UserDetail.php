<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = [
        'firstname',
        'lastname',
        'phonenumber',
        'address',
        "user_id"
    ];
}
