<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date_from')->nullable();   // null = odjakživa
            $table->date('date_to')->nullable();     // null = navždy
            $table->json('days_of_week')->nullable(); // např. ["fri","sat"] ; null = všechny dny
            $table->unsignedInteger('price_per_night'); // v Kč
            $table->unsignedTinyInteger('min_nights')->default(1);
            $table->unsignedSmallInteger('priority')->default(10);
            $table->boolean('is_active')->default(true);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['date_from','date_to']);
            $table->index('priority');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('price_rules');
    }
};
