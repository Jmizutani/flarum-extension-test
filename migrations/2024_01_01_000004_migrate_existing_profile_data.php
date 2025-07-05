<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::schema(function (Blueprint $table) {
    // 既存のプロフィールデータを新しい構造に移行
    $connection = $table->getConnection();
    
    // デフォルトフィールドを作成
    $defaultFields = [
        ['name' => 'introduction', 'label' => '自己紹介', 'type' => 'textarea', 'sort_order' => 1],
        ['name' => 'childcare_situation', 'label' => '子育ての状況', 'type' => 'textarea', 'sort_order' => 2],
        ['name' => 'care_situation', 'label' => '介護の状況', 'type' => 'textarea', 'sort_order' => 3],
    ];
    
    foreach ($defaultFields as $field) {
        $fieldId = $connection->table('profile_fields')->insertGetId([
            'name' => $field['name'],
            'label' => $field['label'],
            'type' => $field['type'],
            'required' => false,
            'sort_order' => $field['sort_order'],
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // 既存のデータを移行
        $connection->table('user_profiles')
            ->whereNotNull($field['name'])
            ->where($field['name'], '!=', '')
            ->chunk(100, function ($profiles) use ($connection, $fieldId, $field) {
                $values = [];
                foreach ($profiles as $profile) {
                    $values[] = [
                        'user_id' => $profile->user_id,
                        'field_id' => $fieldId,
                        'value' => $profile->{$field['name']},
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (!empty($values)) {
                    $connection->table('profile_field_values')->insert($values);
                }
            });
    }
});