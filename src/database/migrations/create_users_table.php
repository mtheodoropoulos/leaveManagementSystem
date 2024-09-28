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

Capsule::schema()->create('employees', static function (Blueprint $table) {
    if (!Capsule::schema()->hasTable('employees')) {
        $table->id();
        $table->foreignId('userId')->constrained('users')->onDelete('cascade');
        $table->integer('employeeCode');
    }
});

Capsule::schema()->create('managers', static function (Blueprint $table) {
    if (!Capsule::schema()->hasTable('managers')) {
        $table->id();
        $table->foreignId('userId')->constrained('users')->onDelete('cascade');
    }
});
