<?php namespace NiaInteractive\Directory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class CreateEntryTagTable extends Migration
{
    public function up()
    {
        Schema::create('niainteractive_directory_entry_tag', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('entry_id');
            $table->unsignedInteger('tag_id');
            $table->string('evidence_url', 500)->nullable();
            $table->string('evidence_level', 20)->default('self_described');
            $table->unique(['entry_id', 'tag_id'], 'nia_dir_entry_tag_unique');
            $table->index('tag_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('niainteractive_directory_entry_tag');
    }
}
