<?php namespace NiaInteractive\Directory\Components;

use Cms\Classes\ComponentBase;
use NiaInteractive\Directory\Models\Category;
use NiaInteractive\Directory\Models\Entry;
use NiaInteractive\Directory\Models\Settings;

class DirectoryList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Directory List',
            'description' => 'Lists published entries, optionally filtered to one category.',
        ];
    }

    public function defineProperties()
    {
        return [
            'categorySlug' => [
                'title'       => 'Category slug',
                'description' => 'Leave blank to list every published entry.',
                'type'        => 'string',
                'default'     => '',
            ],
            'includeChildren' => [
                'title'   => 'Include sub-categories',
                'type'    => 'checkbox',
                'default' => true,
            ],
            'perPage' => [
                'title'      => 'Entries per page',
                'type'       => 'string',
                'default'    => '',
                'validation' => 'numeric',
            ],
        ];
    }

    public function onRun()
    {
        $perPage = (int) $this->property('perPage');
        if ($perPage < 1) {
            $perPage = (int) Settings::get('per_page', 25);
        }

        $slug  = trim((string) $this->property('categorySlug'));
        $query = Entry::published()->listed()->with('kind');

        if ($slug !== '') {
            $category = Category::where('slug', $slug)->first();
            $this->page['category'] = $category;

            if (!$category) {
                $query->whereRaw('1 = 0');
            } else {
                $ids = [$category->id];

                if ($this->property('includeChildren')) {
                    $ids = array_merge($ids, $category->descendantIds());
                }

                $query->whereHas('categories', function ($relation) use ($ids) {
                    $relation->whereIn('niainteractive_directory_categories.id', $ids);
                });
            }
        }

        $this->page['entries'] = $query->orderBy('sort_name')->paginate($perPage);
        $this->page['pageTitle'] = !empty($this->page['category'])
            ? $this->page['category']->name
            : 'Category';
    }
}
