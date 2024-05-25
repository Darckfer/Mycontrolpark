<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tbl_usuarios extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_usuarios')->insert([
            ['nombre' => 'Admin', 'apellidos' => 'admin', 'email' => 'admin@mycontrolpark.com', 'contrasena' => bcrypt('qweQWE123'), 'id_rol' => 1, 'id_empresa' => 11],
            ['nombre' => 'Julio Cesar', 'apellidos' => 'Carrillo', 'email' => 'julio@gmail.com', 'contrasena' => bcrypt('qweQWE123'), 'id_rol' => 2, 'id_empresa' => 2],
            ['nombre' => 'Alberto', 'apellidos' => 'Bermejo', 'email' => 'alberto@gmail.com', 'contrasena' => bcrypt('qweQWE123'), 'id_rol' => 3, 'id_empresa' => 3],
            ['nombre' => 'Iker', 'apellidos' => 'Gomez', 'email' => 'iker@gmail.com', 'contrasena' => bcrypt('qweQWE123'), 'id_rol' => 2, 'id_empresa' => 4],
            ['nombre' => 'Óscar', 'apellidos' => 'López', 'email' => 'oscar@gmail.com', 'contrasena' => bcrypt('qweQWE123'), 'id_rol' => 3, 'id_empresa' => 5],
            ['nombre' => 'Sara', 'apellidos' => 'Rodríguez', 'email' => 'sara@gmail.com', 'contrasena' => bcrypt('qweQWE123'), 'id_rol' => 3, 'id_empresa' => 6],
            ['nombre' => 'Alejandro', 'apellidos' => 'Lay', 'email' => 'alejandro@gmail.com', 'contrasena' => bcrypt('qweQWE123'), 'id_rol' => 3, 'id_empresa' => 7],
            ['nombre' => 'Paula', 'apellidos' => 'García', 'email' => 'paula@gmail.com', 'contrasena' => bcrypt('qweQWE123'), 'id_rol' => 3, 'id_empresa' => 8],
            ['nombre' => 'Pedro', 'apellidos' => 'Bravo', 'email' => 'pedro@gmail.com', 'contrasena' => bcrypt('qweQWE123'), 'id_rol' => 3, 'id_empresa' => 9],
            ['nombre' => 'Ana', 'apellidos' => 'Mena', 'email' => 'ana@gmail.com', 'contrasena' => bcrypt('qweQWE123'), 'id_rol' => 3, 'id_empresa' => 10],
        ]);
    }
}
