<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tbl_parkings extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        // Coordenadas aproximadas de diferentes ciudades de España
        $coordinates = [
            // Madrid
            ['lat' => 40.4168, 'lng' => -3.7038],
            // Barcelona
            ['lat' => 41.3851, 'lng' => 2.1734],
            // Valencia
            ['lat' => 39.4699, 'lng' => -0.3763],
            // Sevilla
            ['lat' => 37.3886, 'lng' => -5.9823],
            // Bilbao
            ['lat' => 43.263, 'lng' => -2.934],
        ];

        // Parkings para cada empresa
        for ($i = 0; $i < count($coordinates); $i++) {
            $lat = $coordinates[$i]['lat'];
            $lng = $coordinates[$i]['lng'];
            $companyId = $i + 1; // ID de empresa
            
            for ($j = 1; $j <= 5; $j++) {
                // Generar coordenadas cercanas
                $parkingLat = $lat + ($j * 0.0004); // Aproximadamente 50 metros de diferencia
                $parkingLng = $lng + ($j * 0.0004); // Aproximadamente 50 metros de diferencia
                
                DB::table('tbl_parkings')->insert([
                    'nombre' => 'Parking ' . $j,
                    'latitud' => $parkingLat,
                    'longitud' => $parkingLng,
                    'id_empresa' => $companyId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
