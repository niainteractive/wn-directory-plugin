<?php namespace NiaInteractive\Directory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class CreateCategoryEntryTable extends Migration
{
    public function up()
    {
        Schema::create('niainteractive_directory_category_entry', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('entry_id');
            $table->boolean('is_primary')->default(false);
            $table->unique(['category_id', 'entry_id'], 'nia_dir_cat_entry_unique');
            $table->index('entry_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('niainteractive_directory_category_entry');
    }
}
