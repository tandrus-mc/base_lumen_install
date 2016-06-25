<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('config_id');
            $table->string('email', 60)->unique();
            $table->string('ip', 60);
            $table->string('referring_url');
            $table->string('home_phone', 60);
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->string('address', 60);
            $table->string('city', 60);
            $table->string('state', 60);
            $table->string('zip', 60);
            $table->string('country', 60);
            $table->string('registration_date', 60);
            $table->string('capture_date', 60);
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
        Schema::drop('leads');
    }
}
