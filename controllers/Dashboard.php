<?php namespace Xitara\Core\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Backend\Widgets\ReportContainer;
use Cms\Classes\Theme;

/**
 * Dashboard Back-end Controller
 */
class Dashboard extends Controller
{
    public $requiredPermissions = [
        'xitara.core.mainmenu',
        'xitara.core.dashboard',
    ];

    public $implement = [
        // 'Backend.Behaviors.FormController',
        // 'Backend.Behaviors.ListController',
    ];

    // public $formConfig = 'config_form.yaml';
    public $listConfig = [
        // 'paybacks' => 'config_payback_list.yaml',
        // 'bonuses' => 'config_bonus_list.yaml',
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContextOwner('Xitara.Core');
        BackendMenu::setContext('Xitara.Core', 'core', 'core.dashboard');
    }

    // public function componentDetails()
    // {
    //     return [
    //         'name' => 'xitara.core::lang.core.dashboard',
    //         'description' => 'xitara.core::lang.core.dashboardDescription',
    //     ];
    // }

    public function index()
    {
        $this->initReportContainer();
        $this->pageTitle = 'xitara.core::lang.plugin.name';

        // $this->asExtension('ListController')->index();
    }

    /**
     * Prepare the report widget used by the dashboard
     *
     * Default config can be overridden by adding a file [THEME]/config/dashboard.yaml
     *
     * @param Model $model
     * @return void
     */
    protected function initReportContainer()
    {
        $config = 'config.yaml';
        if ($theme = Theme::getActiveTheme()) {
            if (file_exists(themes_path($theme->getDirName() . '/config/dashboard.yaml'))) {
                $config = themes_path($theme->getDirName() . '/config/dashboard.yaml');
            }
        }

        $container = new ReportContainer($this, $config);
        $container->bindToController();
        return $container;
    }

    public function index_onInitReportContainer()
    {
        $container = $this->initReportContainer();
        return ['#coreReportContainer' => $container->render()];
    }

    /**
     * Custom permissions check that will redirect to the next
     * available menu item, if permission to this page is denied.
     */
    protected function checkPermissionRedirect()
    {
        if (!$this->user->hasAccess('xitara.core.dashboard')) {
            $true = function () {return true;};
            if ($first = array_first(BackendMenu::listMainMenuItems(), $true)) {
                return Redirect::intended($first->url);
            }
        }
    }
}
