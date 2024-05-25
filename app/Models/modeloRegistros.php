<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class modeloRegistros extends Model
{
    use HasFactory;
    protected $table = 'tbl_registros';

    protected $fillable = [
        'id_usuario',
        'laltitud',
        'longitud',
    ];
}
