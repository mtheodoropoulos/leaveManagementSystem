<?php

declare(strict_types = 1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('users')) {
    Capsule::schema()->create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->timestamps();
    });
}

if (!Capsule::schema()->hasTable('employees')) {
    Capsule::schema()->create('employees', function (Blueprint $table) {
        $table->id();
        $table->foreignId('userId')->constrained('users')->onDelete('cascade');
        $table->integer('employeeCode');
        $table->integer('created_by')->nullable();
    });
}

if (!Capsule::schema()->hasTable('managers')) {
    Capsule::schema()->create('managers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('userId')->constrained('users')->onDelete('cascade');
    });
}
