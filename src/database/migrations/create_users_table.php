<?php

declare(strict_types = 1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::schema()->create('users', function (Blueprint $table) {
    $table->id(); // Primary key (auto-incrementing)
    $table->string('name'); // User's name
    $table->string('email')->unique(); // User's email (unique)
    $table->string('password'); // User's password
    $table->timestamps(); // Created_at and updated_at timestamps
});
