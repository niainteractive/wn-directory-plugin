<?php namespace NiaInteractive\Directory\Components;

use Cms\Classes\ComponentBase;
use NiaInteractive\Directory\Models\Category;
use NiaInteractive\Directory\Models\Entry;

class DirectoryCategories extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Directory Categories',
            'description' => 'Outputs the category tree with a count of listed entries.',
        ];
    }

    public function onRun()
    {
        $rows = Category::tree();

        foreach ($rows as &$row) {
            $row['count'] = Entry::published()->listed()
                ->whereHas('categories', function ($query) use ($row) {
                    $query->where('niainteractive_directory_categories.id', $row['category']->id);
                })
                ->count();
        }
        unset($row);

        $this->page['categories'] = $rows;
        $this->page['pageTitle']  = 'Categories';
    }
}
