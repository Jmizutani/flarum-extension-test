<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable('user_profiles', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id')->unsigned();
    $table->string('facebook_url')->nullable();
    $table->string('x_url')->nullable();
    $table->string('instagram_url')->nullable();
    $table->boolean('is_visible')->default(true);
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->unique('user_id');
});