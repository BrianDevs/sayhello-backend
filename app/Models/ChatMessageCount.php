<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessageCount extends Model
{
    use HasFactory;
    protected $table = 'chat_message_counts';
    protected $fillable = ['receiver_id', 'room_id'];
}
