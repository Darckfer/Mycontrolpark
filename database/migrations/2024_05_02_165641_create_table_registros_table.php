<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tbl_registros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('accion', 255);
            $table->string('tipo', 50);
            $table->unsignedBigInteger('id_usuario');
            $table->decimal('latitud', 10, 6)->nullable();
            $table->decimal('longitud', 10, 6)->nullable();
            $table->unsignedBigInteger('id_reserva')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('tbl_usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_reserva')->references('id')->on('tbl_reservas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_registros');
    }
};
