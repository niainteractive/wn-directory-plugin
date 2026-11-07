<?php namespace NiaInteractive\Directory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class CreateTagsTable extends Migration
{
    public function up()
    {
        Schema::create('niainteractive_directory_tags', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 120);
            $table->string('slug', 160)->unique();
            $table->string('tag_group', 40)->default('reason')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('niainteractive_directory_tags');
    }
}
