<?php namespace NiaInteractive\Directory;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'Nia Directory',
            'description' => 'Nested categories, tagged entries, and directed relations between entries.',
            'author'      => 'Nia Interactive',
            'icon'        => 'icon-folder-open-o',
            'homepage'    => 'https://niainteractive.com',
        ];
    }

    public function registerComponents()
    {
        return [
            'NiaInteractive\Directory\Components\DirectoryCategories'   => 'directoryCategories',
            'NiaInteractive\Directory\Components\DirectoryList'         => 'directoryList',
            'NiaInteractive\Directory\Components\DirectoryEntry'        => 'directoryEntry',
            'NiaInteractive\Directory\Components\DirectoryReplaceIndex' => 'directoryReplaceIndex',
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Nia Directory',
                'description' => 'Labels, pagination and display options.',
                'category'    => 'Nia Directory',
                'icon'        => 'icon-cog',
                'class'       => 'NiaInteractive\Directory\Models\Settings',
                'order'       => 500,
                'keywords'    => 'directory catalog',
            ],
        ];
    }

    public function registerPermissions()
    {
        return [
            'niainteractive.directory.entries' => [
                'tab'   => 'Nia Directory',
                'label' => 'Manage directory entries',
            ],
            'niainteractive.directory.categories' => [
                'tab'   => 'Nia Directory',
                'label' => 'Manage categories',
            ],
            'niainteractive.directory.taxonomy' => [
                'tab'   => 'Nia Directory',
                'label' => 'Manage kinds and tags',
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'directory' => [
                'label'       => 'Directory',
                'url'         => Backend::url('niainteractive/directory/entries'),
                'icon'        => 'icon-folder-open-o',
                'permissions' => ['niainteractive.directory.*'],
                'order'       => 500,
                'sideMenu'    => [
                    'entries' => [
                        'label'       => 'Entries',
                        'url'         => Backend::url('niainteractive/directory/entries'),
                        'icon'        => 'icon-list',
                        'permissions' => ['niainteractive.directory.entries'],
                    ],
                    'categories' => [
                        'label'       => 'Categories',
                        'url'         => Backend::url('niainteractive/directory/categories'),
                        'icon'        => 'icon-sitemap',
                        'permissions' => ['niainteractive.directory.categories'],
                    ],
                    'kinds' => [
                        'label'       => 'Kinds',
                        'url'         => Backend::url('niainteractive/directory/kinds'),
                        'icon'        => 'icon-tags',
                        'permissions' => ['niainteractive.directory.taxonomy'],
                    ],
                    'tags' => [
                        'label'       => 'Tags',
                        'url'         => Backend::url('niainteractive/directory/tags'),
                        'icon'        => 'icon-tag',
                        'permissions' => ['niainteractive.directory.taxonomy'],
                    ],
                ],
            ],
        ];
    }
}
