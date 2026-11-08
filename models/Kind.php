<?php namespace NiaInteractive\Directory\Models;

use Model;

class Kind extends Model
{
    public $table = 'niainteractive_directory_kinds';

    protected $guarded = [];

    public $rules = [
        'name' => 'required|between:2,120',
        'slug' => 'required|between:2,160',
    ];

    public $hasMany = [
        'entries' => [Entry::class, 'key' => 'kind_id'],
    ];

    public function beforeValidate()
    {
        if (!$this->slug && $this->name) {
            $this->slug = \Str::slug($this->name);
        }
    }
}
