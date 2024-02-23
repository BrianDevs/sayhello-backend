<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'chats';
    protected $fillable = ['sender_id' , 'receiver_id', 'message' , 'room_id' , 'message_type', 'created_at', 'image' , 'chat_type' , 'start_chat_date'];
}
