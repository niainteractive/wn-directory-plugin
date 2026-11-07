<?php namespace NiaInteractive\Directory\Components;

use Cms\Classes\ComponentBase;
use NiaInteractive\Directory\Models\Entry;

class DirectoryReplaceIndex extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Directory Replace Index',
            'description' => 'A to Z list of entries that something else can replace.',
        ];
    }

    public function defineProperties()
    {
        return [
            'letter' => [
                'title'       => 'Letter',
                'description' => 'Leave blank to take the letter from the page URL.',
                'type'        => 'string',
                'default'     => '',
            ],
        ];
    }

    public function onRun()
    {
        $letter = strtoupper(trim((string) $this->property('letter')));

        if ($letter === '') {
            $letter = strtoupper((string) $this->param('letter'));
        }

        $query = Entry::published()->replaceTargets()->with('kind');

        if ($letter !== '' && preg_match('/^[A-Z]$/', $letter)) {
            $query->where('sort_name', 'like', $letter . '%');
        } else {
            $letter = '';
        }

        $this->page['entries']  = $query->orderBy('sort_name')->get();
        $this->page['letter']   = $letter;
        $this->page['alphabet'] = range('A', 'Z');
    }
}
