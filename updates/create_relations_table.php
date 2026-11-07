<?php namespace NiaInteractive\Directory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class CreateRelationsTable extends Migration
{
    public function up()
    {
        Schema::create('niainteractive_directory_relations', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('source_entry_id');
            $table->unsignedInteger('target_entry_id');
            $table->string('relation_type', 40)->default('replaces');
            $table->unsignedInteger('rank')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(
                ['source_entry_id', 'target_entry_id', 'relation_type'],
                'nia_dir_relation_unique'
            );
            $table->index(['target_entry_id', 'relation_type'], 'nia_dir_relation_target');
            $table->index(['source_entry_id', 'relation_type'], 'nia_dir_relation_source');
        });
    }

    public function down()
    {
        Schema::dropIfExists('niainteractive_directory_relations');
    }
}
