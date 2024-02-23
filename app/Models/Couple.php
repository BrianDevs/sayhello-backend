<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Couple extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'couples';
    protected $fillable = ['my_id', 'youtube_link','stranger_id','selfie','accepted_lat','accepted_lng','status'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
}
