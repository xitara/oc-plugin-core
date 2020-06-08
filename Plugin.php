<?php namespace Xitara\Core;

use App;
use Backend;
use BackendMenu;
use Config;
use Event;
use System\Classes\PluginBase;
use System\Classes\PluginManager;
use Xitara\Core\Models\Menu;

class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'xitara.core::lang.plugin.name',
            'description' => 'xitara.core::lang.plugin.description',
            'author' => 'xitara.core::lang.plugin.author',
            'homepage' => 'xitara.core::lang.plugin.homepage',
            'icon' => '',
            'iconSvg' => 'plugins/xitara/core/assets/images/icon.svg',
        ];
    }

    public function register()
    {
        BackendMenu::registerContextSidenavPartial(
            'Xitara.Core',
            'core',
            '$/xitara/core/partials/_sidebar.htm'
        );
    }

    public function boot()
    {
        // Check if we are currently in backend module.
        if (!App::runningInBackend()) {
            return;
        }

        /**
         * set new backend-skin
         */
        Config::set('cms.backendSkin', 'Xitara\Core\Classes\BackendSkin');

        /**
         * add items to sidemenu
         */
        $this->getSideMenu('Xitara.Core', 'core');

        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            $controller->addCss('/plugins/xitara/core/assets/css/app.css');
            $controller->addJs('/plugins/xitara/core/assets/js/app.js');
        });
    }

    public function registerSettings()
    {
        return [
            'configs' => [
                'label' => 'xitara.core::lang.config.label',
                'description' => 'xitara.core::lang.config.description',
                'category' => 'xitara.core::core.config.name',
                'icon' => 'icon-settings',
                'class' => 'Xitara\Core\Models\Config',
                'order' => 0,
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'core' => [
                'label' => 'xitara.core::lang.menu.name',
                'url' => Backend::url('xitara/core/dashboard'),
                'icon' => 'icon-leaf',
                'iconSvg' => 'plugins/xitara/core/assets/images/icon-toolbox.svg',
                'permissions' => ['xitara.core.*'],
                'order' => 200,
            ],
        ];
    }

    /**
     * grab sidemenu items
     * $inject contains addidtional menu-items with the following strcture
     *
     * name = [
     *     group => [string],
     *     label => [string],
     *     url => [string], (full backend url)
     *     icon => [string],
     * ]
     *
     * name -> unique name
     * group -> same group where to inject
     * label -> shown name in menu
     * url -> url relative to backend
     *
     * @autor   mburghammer
     * @date    2018-05-15T20:49:04+0100
     * @version 0.0.2
     * @since   0.0.1
     * @param   string                   $owner
     * @param   string                   $code
     * @param   array                   $inject
     */
    public static function getSideMenu(string $owner, string $code)
    {
        $items = [
            'core.dashboard' => [
                'group' => 'xitara.core::lang.core.mainmenu',
                'label' => 'xitara.core::lang.core.dashboard',
                'url' => Backend::url('xitara/core/dashboard'),
                'icon' => 'icon-dashboard',
                'order' => 10,
            ],
            'core.menu' => [
                'group' => 'xitara.core::lang.core.mainmenu',
                'label' => 'xitara.core::lang.core.menu',
                'url' => Backend::url('xitara/core/menu/reorder'),
                'icon' => 'icon-archive',
                'order' => 20,
            ],
            'core.tb3' => [
                'group' => 'xitara.core::lang.core.mainmenu',
                'label' => 'Toolbox 3',
                'url' => 'https://www2.lady-anja.com/backend/xitara/toolbox/dashboard',
                'icon' => 'icon-archive',
                'permissions' => ['xitara.erobridge.erobridge'],
                'target' => '_blank',
                'order' => 30,
            ],
        ];

        foreach (PluginManager::instance()->getPlugins() as $name => $plugin) {
            if (strpos($name, 'Xitara.') !== false) {
                $namespace = str_replace('.', '\\', $name) . '\Plugin';

                if (method_exists($namespace, 'injectSideMenu')) {
                    $inject = $namespace::injectSideMenu();
                    $items = array_merge($items, $inject);
                }
            }
        }

        Event::listen('backend.menu.extendItems', function ($manager) use ($owner, $code, $items) {
            $manager->addSideMenuItems($owner, $code, $items);
        });
    }

    public static function getMenuOrder(String $code): int
    {
        $item = Menu::find($code);

        if ($item === null) {
            return 9999;
        }

        return $item->sort_order;
    }
}
