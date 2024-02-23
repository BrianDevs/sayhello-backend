<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hobby extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'hobbies';
    protected $fillable = ['name', 'is_active'];
}
