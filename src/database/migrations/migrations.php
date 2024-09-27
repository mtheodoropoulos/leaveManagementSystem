<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// Create the migrations table
Capsule::schema()->create('migrations', function (Blueprint $table) {
    $table->id(); // Primary key (auto-incrementing)
    $table->string('migration'); // Migration name
    $table->integer('batch'); // Batch number
});
