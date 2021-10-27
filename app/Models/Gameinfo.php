<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gameinfo extends Model
{
    use HasFactory;

    protected $fillable=[
        "gamename","gameinfo","gameprice",'gamephoto'
    ];
}
