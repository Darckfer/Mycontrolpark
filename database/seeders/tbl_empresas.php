<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tbl_empresas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_empresas')->insert([
            ['nombre' => 'Parkings S.A.'],
            ['nombre' => 'Gestión de Aparcamientos SL'],
            ['nombre' => 'ParkingTech Solutions'],
            ['nombre' => 'Estacionamientos Urbanos'],
            ['nombre' => 'AparcaBien'],
            ['nombre' => 'ParkingExpress'],
            ['nombre' => 'ParkItNow'],
            ['nombre' => 'Aparcamientos Innovadores'],
            ['nombre' => 'Park&Go'],
            ['nombre' => 'Smart Parking'],
            ['nombre' => 'MyControlPark'],
        ]);

    }
}
