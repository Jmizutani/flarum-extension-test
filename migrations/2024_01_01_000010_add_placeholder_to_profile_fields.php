<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::addColumns('profile_fields', [
    'placeholder' => ['string', 'nullable' => true, 'after' => 'type']
]);