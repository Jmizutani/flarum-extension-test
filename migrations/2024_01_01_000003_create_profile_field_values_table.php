<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable('profile_field_values', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id')->unsigned();
    $table->integer('field_id')->unsigned();
    $table->text('value')->nullable();
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('field_id')->references('id')->on('profile_fields')->onDelete('cascade');
    $table->unique(['user_id', 'field_id']);
});