<?php namespace NiaInteractive\Directory\Models;

use Model;
use Str;

class Category extends Model
{
    public $table = 'niainteractive_directory_categories';

    public $rules = [
        'name' => 'required|between:2,120',
        'slug' => 'required|between:2,160',
    ];

    public $belongsTo = [
        'parent' => [Category::class, 'key' => 'parent_id'],
    ];

    public $hasMany = [
        'children' => [Category::class, 'key' => 'parent_id'],
    ];

    public $belongsToMany = [
        'entries' => [
            Entry::class,
            'table'    => 'niainteractive_directory_category_entry',
            'key'      => 'category_id',
            'otherKey' => 'entry_id',
            'pivot'    => ['is_primary'],
        ],
    ];

    public function beforeValidate()
    {
        if (!$this->slug && $this->name) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getParentIdOptions()
    {
        $options = ['' => '— none —'];
        $exclude = $this->descendantIds();

        foreach (static::orderBy('name')->get() as $category) {
            if ($category->id == $this->id || in_array($category->id, $exclude)) {
                continue;
            }
            $options[$category->id] = $category->name;
        }

        return $options;
    }

    public function descendantIds()
    {
        if (!$this->id) {
            return [];
        }

        $ids  = [];
        $edge = [$this->id];

        while (!empty($edge)) {
            $children = static::whereIn('parent_id', $edge)->pluck('id')->all();
            $children = array_diff($children, $ids);
            if (empty($children)) {
                break;
            }
            $ids  = array_merge($ids, $children);
            $edge = $children;
        }

        return $ids;
    }

    /**
     * A flat list of every category with indentation, for browsing.
     */
    public static function tree($parentId = null, $depth = 0)
    {
        $out = [];

        foreach (static::where('parent_id', $parentId)->orderBy('sort_order')->orderBy('name')->get() as $category) {
            $out[] = ['category' => $category, 'depth' => $depth];
            $out = array_merge($out, static::tree($category->id, $depth + 1));
        }

        return $out;
    }
}
