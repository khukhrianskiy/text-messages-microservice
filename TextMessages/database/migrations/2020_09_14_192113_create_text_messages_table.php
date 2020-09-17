<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTextMessagesTable extends Migration
{
    public function up(): void
    {
        Schema::create('text_messages', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            $table->string('status', 50);
            $table->string('phone_number', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('text_messages');
    }
}
