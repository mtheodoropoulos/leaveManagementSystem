<?php

declare(strict_types = 1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::schema()->create('role_user', static function (Blueprint $table) {
    if (!Capsule::schema()->hasTable('role_user')) {
        $table->id();
        $table->foreignId('user_id')->constrained("users")->onDelete('cascade');
        $table->foreignId('role_id')->constrained("roles")->onDelete('cascade');
        $table->timestamps();
    }
});
