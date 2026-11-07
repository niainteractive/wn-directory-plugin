<?php namespace NiaInteractive\Directory\Components;

use Cms\Classes\ComponentBase;
use NiaInteractive\Directory\Models\Category;

class DirectoryCategories extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Directory Categories',
            'description' => 'Outputs the category tree, including sub-categories.',
        ];
    }

    public function onRun()
    {
        $this->page['categories'] = Category::tree();
    }
}
