<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTables extends Migration
{
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->timestamps();
        });
        Schema::create('tasks', static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->string('status');
            $table->integer('priority');
            $table->string('name');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
