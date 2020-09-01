<?php namespace Xitara\Core;

use App;
use Backend;
use BackendMenu;
use Config;
use Event;
use Log;
use Str;
use System\Classes\PluginBase;
use System\Classes\PluginManager;
use Xitara\Core\Models\CustomMenu;
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
                'icon' => 'icon-wrench',
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
                'label' => 'xitara.core::lang.submenu.label',
                'url' => Backend::url('xitara/core/dashboard'),
                'icon' => 'icon-leaf',
                'iconSvg' => 'plugins/xitara/core/assets/images/icon-toolbox.svg',
                'permissions' => ['xitara.core.*'],
                'order' => 50,
            ],
        ];
    }

    /**
     * grab sidemenu items
     * $inject contains addidtional menu-items with the following strcture
     *
     * name = [
     *     group => [string],
     *     label => string|'placeholder', // placeholder only
     *     url => [string], (full backend url)
     *     icon => [string],
     *     'attributes' => [
     *         'target' => [string],
     *         'placeholder' => true|false, // placeholder after elm
     *         'keywords' => [string],
     *         'description' => [string],
     *     ],
     * ]
     *
     * name -> unique name
     * group -> name to sort menu items
     * label -> shown name in menu
     * url -> url relative to backend
     * icon -> icon left of label
     * attribures -> array (optional)
     *     target -> _blank|_self (optional)
     *     keywords -> only for searching (optional)
     *     description -> showed under label (optional)
     *
     * @autor   mburghammer
     * @date    2018-05-15T20:49:04+0100
     * @version 0.0.3
     * @since   0.0.1
     * @since   0.0.2 added groups
     * @since   0.0.3 added attributes
     * @param   string                   $owner
     * @param   string                   $code
     * @param   array                   $inject
     */
    public static function getSideMenu(string $owner, string $code)
    {
        $items = [
            'core.dashboard' => [
                'label' => 'xitara.core::lang.core.dashboard',
                'group' => 'xitara.core::lang.submenu.label',
                'url' => Backend::url('xitara/core/dashboard'),
                'icon' => 'icon-dashboard',
                'order' => 1,
                'attributes' => [
                ],
            ],
            'core.menu' => [
                'label' => 'xitara.core::lang.core.menu',
                'group' => 'xitara.core::lang.submenu.label',
                'url' => Backend::url('xitara/core/menu/reorder'),
                'icon' => 'icon-sort',
                'order' => 2,
                'attributes' => [
                ],
            ],
            'core.custommenus' => [
                'label' => 'xitara.core::lang.custommenu.label',
                'group' => 'xitara.core::lang.submenu.label',
                'url' => Backend::url('xitara/core/custommenus'),
                'icon' => 'icon-link',
                'order' => 3,
                'attributes' => [
                ],
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
        // var_dump(count($items));
        // var_dump($items);

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

    /**
     * inject into sidemenu
     * @autor   mburghammer
     * @date    2020-06-26T21:13:34+02:00
     *
     * @see Xitara\Core::getSideMenu
     * @return  array                   sidemenu-data
     */
    public static function injectSideMenu()
    {
        Log::debug(__METHOD__);

        $custommenus = custommenu::where('is_submenu', 1)
            ->where('is_active', 1)
            ->get();

        $inject = [];
        foreach ($custommenus as $custommenu) {
            $count = 0;
            foreach ($custommenu->links as $text => $link) {
                if ($link['is_active'] == 1) {
                    $inject['custommenulist.' . $custommenu->slug . '.' . Str::slug($link['text'])] = [
                        'label' => $link['text'],
                        'group' => 'xitara.custommenulist.' . $custommenu->slug,
                        'url' => $link['link'],
                        'icon' => 'icon-archive',
                        'permissions' => ['submenu.custommenu.' . $custommenu->slug . '.'
                            . Str::slug($link['text'])],
                        'attributes' => [
                            'target' => ($link['is_blank'] == 1) ? '_blank' : null,
                            'keywords' => $link['keywords'] ?? null,
                            'description' => $link['description'] ?? null,
                        ],
                        'order' => self::getMenuOrder('xitara.custommenulist.' . $custommenu->slug) + $count++,
                    ];
                }
            }
        }

        // var_dump($inject);

        // return [];
        return $inject;
    }
}
