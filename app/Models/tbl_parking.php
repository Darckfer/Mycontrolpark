<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_parking extends Model
{
    use HasFactory;

    protected $table = 'tbl_parkings';  // Nombre de la tabla
    protected $primaryKey = 'id';  // Clave primaria

    // Permite asignación masiva en estos campos
    protected $fillable = [
        'nombre', 'latitud', 'longitud', 'id_empresa'
    ];

    // Relaciones con otros modelos
    public function empresa() {
        return $this->belongsTo(tbl_empresas::class, 'id_empresa');
    }

    public function plazas() {
        return $this->hasMany(tbl_plazas::class, 'id_parking');
    }

    // Métodos personalizados (como un scope con relaciones)
    public function scopeWithEmpresa($query) {
        return $query->with('empresa');
    }
}
