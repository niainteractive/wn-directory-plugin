<?php namespace NiaInteractive\Directory\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Entries extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $requiredPermissions = ['niainteractive.directory.entries'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('NiaInteractive.Directory', 'directory', 'entries');
    }
}
