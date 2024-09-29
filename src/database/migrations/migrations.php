<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::schema()->create('migrations', function (Blueprint $table) {
    $table->id();
    $table->string('migration');
    $table->integer('batch');
});
