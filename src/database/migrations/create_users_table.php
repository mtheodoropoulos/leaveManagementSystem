<?php

declare(strict_types = 1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::schema()->create('users', function (Blueprint $table) {
    if (!Capsule::schema()->hasTable('users')) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->timestamps();
    }
});
