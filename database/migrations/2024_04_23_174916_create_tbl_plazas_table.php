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
        Schema::create('tbl_plazas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 45);
            $table->decimal('planta', 8, 2);
            $table->unsignedBigInteger('id_estado');
            $table->unsignedBigInteger('id_parking');
            $table->timestamps();

            $table->foreign('id_estado')->references('id')->on('tbl_estados')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_parking')->references('id')->on('tbl_parkings')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_plazas');
    }
};