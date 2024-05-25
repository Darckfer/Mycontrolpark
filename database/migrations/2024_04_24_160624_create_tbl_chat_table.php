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
        Schema::create('tbl_chats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('mensaje');
            $table->unsignedBigInteger('emisor');
            $table->unsignedBigInteger('receptor')->nullable();
            $table->timestamps();

            $table->foreign('emisor')->references('id')->on('tbl_usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('receptor')->references('id')->on('tbl_usuarios')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_chats');
    }
};
