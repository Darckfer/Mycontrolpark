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
        Schema::create('tbl_fotos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_reserva');
            $table->string('ruta');
            $table->timestamps();

            $table->foreign('id_reserva')->references('id')->on('tbl_reservas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('tbl_fotos');
    }
};
