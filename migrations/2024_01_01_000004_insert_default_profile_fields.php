<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $connection = $schema->getConnection();
        
        $defaultFields = [
            ['name' => 'introduction', 'label' => '自己紹介', 'type' => 'textarea', 'placeholder' => 'あなたの自己紹介を入力してください', 'sort_order' => 1],
        ];
        
        foreach ($defaultFields as $field) {
            $connection->table('profile_fields')->insert([
                'name' => $field['name'],
                'label' => $field['label'],
                'type' => $field['type'],
                'placeholder' => $field['placeholder'],
                'required' => false,
                'sort_order' => $field['sort_order'],
                'is_active' => true,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }
    },
    
    'down' => function (Builder $schema) {
        $connection = $schema->getConnection();
        $connection->table('profile_fields')->where('name', 'introduction')->delete();
    }
];