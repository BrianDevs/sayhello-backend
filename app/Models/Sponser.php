<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sponser extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'sponsers';
    protected $fillable = ['title', 'image' , 'is_active' , 'url' , 'description'];
}
