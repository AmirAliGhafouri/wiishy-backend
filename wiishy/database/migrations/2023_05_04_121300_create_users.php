<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',60);
            $table->string('family',60);
            $table->date('userBirthday');
            $table->integer('userLocationid');
            $table->integer('userGender');
            $table->text('userDescription');
            $table->string('userImageUrl');
            $table->integer('status')->default(1);
            $table->integer('userCode');
            $table->integer('followers')->default(0);
            $table->integer('followings')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
