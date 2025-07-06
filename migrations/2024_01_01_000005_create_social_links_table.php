<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable(
    'social_links',
    function (Blueprint $table) {
        $table->increments('id');
        $table->string('name')->unique();
        $table->string('label');
        $table->text('icon_url')->nullable();
        $table->string('url_pattern')->nullable();
        $table->integer('sort_order')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    }
);