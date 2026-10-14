<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('history_events', function (Blueprint $table) {
            $table->id();
            $table->string('event');
            $table->morphs('subject'); // subject_type, subject_id
            $table->foreignId('reservation_id')->nullable()->constrained()->nullOnDelete();
            $table->nullableMorphs('causer');
            $table->string('message')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index('event');
            $table->index('created_at');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('history_events');
    }
};
