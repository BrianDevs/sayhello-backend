<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatBox extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'chat_boxes';
    protected $fillable = ['my_id , stranger_id, dateTime ,status'];

}
