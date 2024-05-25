<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_empresas extends Model
{
    use HasFactory;

    protected $table = 'tbl_empresas';

    protected $fillable = [
        'nombre',
    ];

    public function parkings()
    {
        return $this->hasMany(tbl_parking::class, 'id_empresa');
    }
}