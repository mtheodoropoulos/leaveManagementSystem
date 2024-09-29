<?php

declare(strict_types = 1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('role_user')) {
    Capsule::schema()->create('role_user', static function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained("users")->onDelete('cascade');
        $table->foreignId('role_id')->constrained("roles")->onDelete('cascade');
        $table->timestamps();
    });
}
