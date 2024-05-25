<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblChat extends Model
{
    use HasFactory;

    protected $table = 'tbl_chats';

    protected $fillable = [
        'emisor',
        'receptor',
        'mensaje',
    ];
}
