<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable(
    'user_social_links',
    function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('user_id');
        $table->unsignedInteger('social_link_id');
        $table->string('value');
        $table->timestamps();
        
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('social_link_id')->references('id')->on('social_links')->onDelete('cascade');
        $table->unique(['user_id', 'social_link_id']);
    }
);