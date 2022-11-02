<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLikeableTables extends Migration
{
    public function up()
    {
        Schema::create(config('laravel-view-counter.views_table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('viewable_id', 36);
            $table->string('viewable_type', 255);
            $table->string('user_id', 36)->index();
            $table->timestamps();
            $table->unique(['viewable_id', 'viewable_type', 'user_id'], 'viewable_views_unique');
        });

        Schema::create(config('laravel-view-counter.views_counter_table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('viewable_id', 36);
            $table->string('viewable_type', 255);
            $table->unsignedBigInteger('count')->default(0);
            $table->unique(['viewable_id', 'viewable_type'], 'viewable_counts');
        });
    }

    public function down()
    {
        Schema::drop(config('laravel-view-counter.views_table_name'));
        Schema::drop(config('laravel-view-counter.views_counter_table_name'));
    }
}