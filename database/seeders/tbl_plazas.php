<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tbl_plazas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los parkings
        $parkings = DB::table('tbl_parkings')->get();
                
        foreach ($parkings as $parking) {
            $parkingId = $parking->id;
            
            for ($i = 1; $i <= 10; $i++) {
                DB::table('tbl_plazas')->insert([
                    'nombre' => 'Plaza ' . $i,
                    'planta' => 1.00,
                    'id_estado' => 2, // Estado por defecto: Libre
                    'id_parking' => $parkingId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
