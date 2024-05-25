<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblEstado extends Model
{
    use HasFactory;

    protected $table = 'tbl_estados';

    protected $fillable = [
        'nombre',
    ];
}
