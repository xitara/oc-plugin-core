# Xitara Core Plugin [![devDependency Status](https://david-dm.org/xitara/oc-plugin-core/dev-status.svg)](https://david-dm.org/xitara/oc-plugin-core/?type=dev) [![Known Vulnerabilities](https://snyk.io/test/github/xitara/oc-plugin-core/badge.svg)](https://snyk.io//test/github/xitara/oc-plugin-core)

Implements backend sidemenu, custom menus, menu sorting

## Getting started

- clone the repo to folder `plugins/xitara/core`
- cd to `plugins/xitara/core`
- run `yarn` to fetch all the dependencies

## Commands

- `start` - start the dev server
- `cleanup` - remove compiled data, node_modules, vendor, etc. don't delete any sources
- `watch` - start webpack --watch
- `dwatch` - start webpack --watch --mode development
- `build` - build the complete app including copying static content
- `dbuild` - build the complete app including copying static content with --mode development
- `zip` - zips a package with only needed files without overhead
- `deploy` - deploys a package with only needed files without overhead in a folder without zipping
- `ftp` - uploads a minimizes package to a configured server (needs lftp)
- `analyze` - analyze your production bundle
- `lint-code` - run an ESLint check
- `lint-style` - run a Stylelint check
- `check-eslint-config` - check if ESLint config contains any rules that are unnecessary or conflict with Prettier
- `check-stylelint-config` - check if Stylelint config contains any rules that are unnecessary or conflict with Prettier

## Register new Plugin to Sidemenu

### Add on top of Plugin.php
```php
use App;
use Backend;
use BackendMenu;
use Event;
```

### Add to boot() method to catch event and display new sidemenu.
```php
Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
    $namespace = (new \ReflectionObject($controller))->getNamespaceName();

    if ($namespace == '[VENDOR]\[PLUGIN]\Controllers') {
        Core::getSideMenu('[VENDOR].[PLUGIN]', '[PLUGIN-SLUG]');
    }
});
```

### Register partial
```php
public function register()
{
    BackendMenu::registerContextSidenavPartial(
        '[VENDOR].[PLUGIN]',
        '[PLUGIN-SLUG]',
        '$/xitara/core/partials/_sidebar.htm'
    );

    ...
}
```

### Extend your navigation label with ::hidden to hide it from top navigation
```php
public function registerNavigation()
{
    return [
        '[PLUGIN-SLUG]' => [
            'label' => '[PLUGIN]::hidden',
            'url' => Backend::url('[VENDOR]/[PLUGIN-SLUG]/mycontroller'),
            'icon' => 'icon-leaf',
            'permissions' => ['[VENDOR].[PLUGIN-SLUG].*'],
            'order' => 500,
        ],
    ];
}
```

### Inject menu items
```php
public static function injectSideMenu()
{
    $i = 0;
    return [
        '[PLUGIN-SLUG].[CONTROLLER]' => [
            'label' => '[VENDOR].[PLUGIN-SLUG]::lang.submenu.[CONTROLLER]',
            'url' => Backend::url('[VENDOR]/[PLUGIN-SLUG]/[CONTROLLER]'),
            'icon' => 'icon-archive',
            'permissions' => ['[VENDOR].[PLUGIN-SLUG].*'],
            'attributes' => [ // can be extendet if you need, no limitations
                'group' => '[VENDOR].[PLUGIN-SLUG]::lang.submenu.label',
                'level' => 1, // optional, default is level 0. adds css-class level-X to li
            ],
            'order' => Core::getMenuOrder('[VENDOR].[PLUGIN-SLUG]') + $i++,
        ],
        ...
    ];
}
```

## Translation

- `[VENDOR].[PLUGIN-SLUG]::lang.submenu.label` is the heading of your menu items
- `[VENDOR].[PLUGIN-SLUG]::lang.submenu.[CONTROLLER]` is the your menu item

## Register backend configs
On top of `Plugin.php`:
```php
use Xitara\Core\Models\Config;
```

and as registration method
```php
public function registerSettings()
{
    if (($category = Config::get('menu_text')) == '') {
        $category = 'xitara.core::core.config.name';
    }

    return [
        'configs' => [
            'category' => $category,
            'label' => '[VENDOR_SLUG].[PLUGIN_SLUG]::lang.submenu.label',
            'description' => '[VENDOR_SLUG].[PLUGIN_SLUG]::lang.submenu.description',
            'icon' => 'icon-comments-o',
            'class' => '[VENDOR]\[PLUGIN]\Models\Config',
            'order' => 20,
        ],
    ];
}
```
