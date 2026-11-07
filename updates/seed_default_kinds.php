<?php namespace NiaInteractive\Directory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

use NiaInteractive\Directory\Models\Kind;

class SeedDefaultKinds extends Migration
{
    public function up()
    {
        $defaults = [
            ['name' => 'Product',      'slug' => 'product',      'sort_order' => 10],
            ['name' => 'Service',      'slug' => 'service',      'sort_order' => 20],
            ['name' => 'Software',     'slug' => 'software',     'sort_order' => 30],
            ['name' => 'Organization', 'slug' => 'organization', 'sort_order' => 40],
        ];

        foreach ($defaults as $row) {
            Kind::firstOrCreate(['slug' => $row['slug']], $row);
        }
    }

    public function down()
    {
        // Kinds are user data once seeded; do not delete on rollback.
    }
}
