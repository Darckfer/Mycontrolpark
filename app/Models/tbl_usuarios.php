<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_usuarios extends Model
{
    use HasFactory;

    protected $table = 'tbl_usuarios';

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'contrasena',
        'id_rol',
        'id_empresa',
    ];
}
