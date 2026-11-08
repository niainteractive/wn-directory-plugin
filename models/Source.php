<?php namespace NiaInteractive\Directory\Models;

use Model;

class Source extends Model
{
    public $table = 'niainteractive_directory_sources';

    protected $guarded = [];

    public $rules = [
        'url' => 'required|url|max:500',
    ];

    public $belongsTo = [
        'entry' => [Entry::class, 'key' => 'entry_id'],
    ];
}
