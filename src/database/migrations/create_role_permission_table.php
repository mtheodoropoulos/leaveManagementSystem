<?php

declare(strict_types = 1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('role_permission')) {
    Capsule::schema()->create('role_permission', static function (Blueprint $table) {
        $table->id();
        $table->foreignId('role_id')->constrained("roles")->onDelete('cascade');
        $table->foreignId('permission_id')->constrained("permissions")->onDelete('cascade');
        $table->timestamps();
    });
}
