<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_plazas extends Model
{
    use HasFactory;

    protected $table = 'tbl_plazas';

    protected $fillable = [
        'nombre',
        'planta',
        'id_estado',
        'id_parking',
    ];

    public function parking()
    {
        return $this->belongsTo(tbl_parking::class, 'id_parking');
    }

    public function estado()
    {
        return $this->belongsTo(tbl_estados::class, 'id_estado');
    }
}
