<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tbl_parking extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_parking')->insert([
            'nombre' => 'Canserra',
            'latitud' => '4555',
            'longitud' => '4555',
            'id_empresa' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('tbl_parking')->insert([
            'nombre' => 'Platos',
            'latitud' => '4555',
            'longitud' => '4555',
            'id_empresa' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
