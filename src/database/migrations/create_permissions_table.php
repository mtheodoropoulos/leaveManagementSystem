<?php

declare(strict_types = 1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('permissions')) {
    Capsule::schema()->create('permissions', static function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->timestamps();

    });
}
