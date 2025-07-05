<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::schema(function (Blueprint $table) {
    // 旧カラムを削除
    $table->getConnection()->getSchemaBuilder()->table('user_profiles', function (Blueprint $table) {
        $table->dropColumn(['introduction', 'childcare_situation', 'care_situation']);
    });
});