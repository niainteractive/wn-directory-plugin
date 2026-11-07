<?php namespace NiaInteractive\Directory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class CreateEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('niainteractive_directory_entries', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 120);
            $table->string('sort_name', 120)->nullable();
            $table->string('slug', 160)->unique();
            $table->unsignedInteger('kind_id')->nullable()->index();
            $table->text('summary')->nullable();
            $table->longText('body')->nullable();
            $table->string('website_url', 500)->nullable();
            $table->string('price_note', 255)->nullable();
            $table->string('region', 120)->nullable();
            $table->text('meta')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->string('listing', 20)->default('listed')->index();
            $table->date('last_verified')->nullable();
            $table->string('verified_by', 120)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('niainteractive_directory_entries');
    }
}
