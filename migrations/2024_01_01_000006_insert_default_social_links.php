<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $connection = $schema->getConnection();
        
        // Insert default social links
        $connection->table('social_links')->insert([
            [
                'name' => 'facebook',
                'label' => 'Facebook',
                'icon_url' => 'https://img.icons8.com/color/48/facebook-new.png',
                'url_pattern' => 'https://facebook.com/{username}',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'name' => 'x',
                'label' => 'X (Twitter)',
                'icon_url' => 'https://img.icons8.com/color/48/twitterx--v1.png',
                'url_pattern' => 'https://x.com/{username}',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'name' => 'instagram',
                'label' => 'Instagram',
                'icon_url' => 'https://img.icons8.com/color/48/instagram-new--v1.png',
                'url_pattern' => 'https://instagram.com/{username}',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        ]);
    },
    
    'down' => function (Builder $schema) {
        $connection = $schema->getConnection();
        $connection->table('social_links')->whereIn('name', ['facebook', 'x', 'instagram'])->delete();
    }
];