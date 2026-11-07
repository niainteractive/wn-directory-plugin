<?php namespace NiaInteractive\Directory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('niainteractive_directory_categories', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 120);
            $table->string('slug', 160)->unique();
            $table->unsignedInteger('parent_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('niainteractive_directory_categories');
    }
}
