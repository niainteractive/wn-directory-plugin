<?php namespace NiaInteractive\Directory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class CreateSourcesTable extends Migration
{
    public function up()
    {
        Schema::create('niainteractive_directory_sources', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('entry_id')->index();
            $table->string('url', 500);
            $table->date('retrieved_on')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('niainteractive_directory_sources');
    }
}
