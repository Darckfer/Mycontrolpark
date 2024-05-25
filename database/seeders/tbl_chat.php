<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tbl_chat extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_chat')->insert([
            'mensaje' => '¡Hola! ¿Cómo estás?',
            'emisor' => 1,
            'receptor' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('tbl_chat')->insert([
            'mensaje' => '¡Bien, gracias! ¿Y tú?',
            'emisor' => 2,
            'receptor' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
