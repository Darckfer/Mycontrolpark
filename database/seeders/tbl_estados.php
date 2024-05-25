<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tbl_estados extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_estados')->insert([
            'nombre' => 'Ocupado',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('tbl_estados')->insert([
            'nombre' => 'Libre',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('tbl_estados')->insert([
            'nombre' => 'Reservado',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
