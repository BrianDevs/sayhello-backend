<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryChat extends Model
{
    use HasFactory;
    protected $table = 'temporary_chats';
    protected $fillable = ['sender_id', 'receiver_id', 'room_id' , 'image' , 'message', 'start_chat_date' , 'message_type'];
}
