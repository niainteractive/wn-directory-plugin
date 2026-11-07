<?php namespace NiaInteractive\Directory\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Kinds extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = ['niainteractive.directory.taxonomy'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('NiaInteractive.Directory', 'directory', 'kinds');
    }
}
