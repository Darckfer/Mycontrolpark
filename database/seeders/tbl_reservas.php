<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class tbl_reservas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $startDate = Carbon::now()->addWeek()->startOfWeek();

        for ($i = 0; $i < 150; $i++) {
            $startDate->addMinutes(rand(30, 720)); // Añadir un intervalo de tiempo aleatorio entre 30 minutos y 12 horas

            $fechaSalida = $startDate->copy()->addHours(rand(24, 24*7)); // Añadir una duración de reserva aleatoria entre 24 horas y 7 semanas

            DB::table('tbl_reservas')->insert([
                'id_trabajador' => null,
                'id_plaza' => $faker->numberBetween(1, 250), // Suponiendo que hay 250 plazas en total
                'nom_cliente' => $faker->name,
                'matricula' => $faker->regexify('[0-9]{4}[A-Z]{3}'),
                'marca' => $faker->randomElement(['Ford', 'Renault', 'Seat', 'Volkswagen', 'Peugeot']),
                'modelo' => $faker->randomElement(['Fiesta', 'Clio', 'Ibiza', 'Polo', '208']),
                'color' => $faker->safeColorName,
                'num_telf' => $faker->numerify('#########'),
                'email' => $faker->email,
                'ubicacion_entrada' => $faker->randomElement(['Aeropuerto T1', 'Aeropuerto T2', 'Puerto']),
                'ubicacion_salida' => $faker->randomElement(['Aeropuerto T1', 'Aeropuerto T2', 'Puerto']),
                'fecha_entrada' => $startDate->toDateTimeString(),
                'fecha_salida' => $fechaSalida->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $startDate = $fechaSalida->copy();
        }
    }
}
