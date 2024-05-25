<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class modeloPlazas extends Model
{
    use HasFactory;
    protected $table = 'tbl_plazas';

    protected $fillable = [
        'nombre',
        'planta',
        'id_estado',
        'id_parking',
    ];
}
