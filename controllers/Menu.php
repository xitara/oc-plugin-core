<?php namespace Xitara\Core\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\PluginManager;
use Xitara\Core\Models\Menu as MenuModel;

/**
 * Menu Back-end Controller
 */
class Menu extends Controller
{
    public $implement = [
        'Backend.Behaviors.ReorderController',
    ];

    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Xitara.Core', 'core', 'core.menu');
        $this->collectMenuItems();
    }

    private function collectMenuItems()
    {
        foreach (PluginManager::instance()->getPlugins() as $name => $plugin) {
            if (strpos($name, 'Xitara.') !== false) {
                $namespace = str_replace('.', '\\', $name) . '\Plugin';

                if (method_exists($namespace, 'injectSideMenu')) {
                    $menu = $namespace::injectSideMenu();

                    if (empty($menu)) {
                        continue;
                    }

                    $item = array_shift($menu);

                    $model = MenuModel::find($item['group']);

                    if ($model !== null) {
                        continue;
                    }

                    $model = new MenuModel;
                    $model->code = $item['group'];
                    $model->sort_order = 9999;
                    $model->save();
                }
            }
        }

        /**
         * resort sort_order
         */
        $i = 100;
        foreach (MenuModel::orderBy('sort_order', 'asc')->get() as $model) {
            $model->sort_order = $i;
            $model->save();
            $i += 100;
        }
    }
}
