<?php

declare(strict_types = 1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('leaves')) {
    Capsule::schema()->create('leaves', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('userId');
        $table->foreign('userId')->references('userId')->on('employees')->onDelete('cascade');
        $table->date('date_requested');
        $table->date('date_approved')->nullable();
        $table->string('status');
        $table->date('date_from');
        $table->date('date_to');
        $table->text('reason');
        $table->integer('approved_by')->nullable();
        $table->timestamps();
    });
}
