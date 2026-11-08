<?php namespace NiaInteractive\Directory\Models;

use Model;

class Tag extends Model
{
    public $table = 'niainteractive_directory_tags';

    protected $guarded = [];

    public $rules = [
        'name' => 'required|between:2,120',
        'slug' => 'required|between:2,160',
        'tag_group' => 'required|max:40',
    ];

    public $belongsToMany = [
        'entries' => [
            Entry::class,
            'table' => 'niainteractive_directory_entry_tag',
            'key' => 'tag_id',
            'otherKey' => 'entry_id',
            'pivot' => ['evidence_url', 'evidence_level'],
        ],
    ];

    public function beforeValidate()
    {
        if (!$this->slug && $this->name) {
            $this->slug = \Str::slug($this->name);
        }
    }

    public function getTagGroupOptions()
    {
        return [
            'ownership' => 'Ownership',
            'licence'   => 'Licence',
            'ideology'  => 'Ideology',
            'reason'    => 'Reason',
            'other'     => 'Other',
        ];
    }
}
