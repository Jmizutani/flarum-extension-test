<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable('profile_fields', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->string('label');
    $table->enum('type', ['text', 'textarea']);
    $table->boolean('required')->default(false);
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});