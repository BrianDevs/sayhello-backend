<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zodiac extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'zodiacs'; 
    protected $fillable = ['name', 'is_active']; 
}
