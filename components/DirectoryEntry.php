<?php namespace NiaInteractive\Directory\Components;

use Cms\Classes\ComponentBase;
use NiaInteractive\Directory\Models\Entry;

class DirectoryEntry extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Directory Entry',
            'description' => 'Shows one entry with its alternatives and what it replaces.',
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Entry slug',
                'description' => 'Leave blank to take the slug from the page URL.',
                'type'        => 'string',
                'default'     => '',
            ],
        ];
    }

    public function onRun()
    {
        $slug  = trim((string) $this->property('slug')) ?: $this->param('slug');
        $entry = Entry::published()->where('slug', $slug)->first();

        if (!$entry) {
            $this->page['entry'] = null;
            return;
        }

        $this->page['entry']        = $entry;
        $this->page['alternatives'] = $entry->alternatives()->get();
        $this->page['replaced']     = $entry->replacedEntries()->get();
        $this->page['isStale']      = $entry->isStale();
    }
}
