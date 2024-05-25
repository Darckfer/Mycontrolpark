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
        Schema::create('tbl_usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 45);
            $table->string('apellidos', 45);
            $table->string('email', 150);
            $table->string('contrasena', 255);
            $table->unsignedBigInteger('id_rol');
            $table->unsignedBigInteger('id_empresa')->nullable();
            $table->timestamps();

            $table->foreign('id_rol')->references('id')->on('tbl_roles')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('tbl_usuarios');
    }
};