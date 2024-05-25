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
        Schema::create('tbl_parkings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 45);
            $table->longText('latitud');
            $table->longText('longitud');
            $table->unsignedBigInteger('id_empresa');
            $table->timestamps();

            $table->foreign('id_empresa')->references('id')->on('tbl_empresas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_parkings');
    }
};