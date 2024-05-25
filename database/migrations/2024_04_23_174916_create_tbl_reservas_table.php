<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tbl_reservas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_trabajador')->nullable();
            $table->unsignedBigInteger('id_plaza')->nullable();
            $table->string('nom_cliente', 45);
            $table->string('matricula', 10);
            $table->string('marca', 15);
            $table->string('modelo', 15);
            $table->string('color', 15)->nullable();
            $table->string('num_telf', 20);
            $table->string('email', 45);
            $table->enum('ubicacion_entrada', ['Aeropuerto T1', 'Aeropuerto T2', 'Puerto']);
            $table->enum('ubicacion_salida', ['Aeropuerto T1', 'Aeropuerto T2', 'Puerto']);
            $table->dateTime('fecha_entrada');
            $table->dateTime('fecha_salida');
            $table->string('firma_entrada', 75)->nullable();
            $table->string('firma_salida', 75)->nullable();
            $table->string('notas')->nullable(); // Nuevo campo 'notas' agregado como VARCHAR y permitiendo valores nulos
            $table->timestamps();

            $table->foreign('id_trabajador')->references('id')->on('tbl_usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_plaza')->references('id')->on('tbl_plazas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_reservas');
    }
};
