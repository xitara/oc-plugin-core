<?php namespace Xitara\Core\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Dashboard Back-end Controller
 */
class Dashboard extends Controller
{
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Xitara.Core', 'core', 'dashboard');
    }

    public function componentDetails()
    {
        return [
            'name' => 'xitara.core::lang.core.dashboard',
            'description' => 'xitara.core::lang.core.dashboardDescription',
        ];
    }

    public function index()
    {
        // var_dump(Session::all());
        // exit;
    }
}
