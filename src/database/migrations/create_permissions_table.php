<?php

declare(strict_types = 1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::schema()->create('permissions', static function (Blueprint $table) {
    if (!Capsule::schema()->hasTable('permissions')) {
        $table->id();
        $table->string('name')->unique();
        $table->timestamps();
    }
});
