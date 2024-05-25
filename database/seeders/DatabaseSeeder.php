<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(tbl_estados::class);
        $this->call(tbl_roles::class);
        $this->call(tbl_empresas::class);
        $this->call(tbl_usuarios::class);
        $this->call(tbl_parkings::class);
        $this->call(tbl_plazas::class);
        $this->call(tbl_reservas::class);
    }
}
