<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return [
    // プロフィールフィールド定義テーブル
    Migration::createTable('profile_fields', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->string('label');
        $table->enum('type', ['text', 'textarea']);
        $table->boolean('required')->default(false);
        $table->integer('sort_order')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    }),
    
    // デフォルトフィールドを作成
    Migration::schema(function () {
        $connection = \Illuminate\Database\Capsule\Manager::connection();
        
        $defaultFields = [
            ['name' => 'introduction', 'label' => '自己紹介', 'type' => 'textarea', 'sort_order' => 1],
            ['name' => 'childcare_situation', 'label' => '子育ての状況', 'type' => 'textarea', 'sort_order' => 2],
            ['name' => 'care_situation', 'label' => '介護の状況', 'type' => 'textarea', 'sort_order' => 3],
        ];
        
        foreach ($defaultFields as $field) {
            $connection->table('profile_fields')->insert([
                'name' => $field['name'],
                'label' => $field['label'],
                'type' => $field['type'],
                'required' => false,
                'sort_order' => $field['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    })
];