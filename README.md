# Xitara Core Plugin [![devDependency Status](https://david-dm.org/xitara/webpack-boilerplate/dev-status.svg)](https://david-dm.org/xitara/webpack-boilerplate/?type=dev) [![Known Vulnerabilities](https://snyk.io/test/github/xitara/webpack-boilerplate/badge.svg)](https://snyk.io//test/github/xitara/webpack-boilerplate)

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
        $i = 0;
        return [
            '[PLUGIN-SLUG].[CONTROLLER]' => [
                'label' => '[VENDOR].[PLUGIN-SLUG]::lang.submenu.[CONTROLLER]',
                'group' => '[VENDOR].[PLUGIN-SLUG]::lang.submenu.label',
                'url' => Backend::url('[VENDOR]/[PLUGIN-SLUG]/[CONTROLLER]'),
                'icon' => 'icon-archive',
                'permissions' => ['[VENDOR].[PLUGIN-SLUG].*'],
                'order' => Core::getMenuOrder('[VENDOR].[PLUGIN-SLUG]') + $i++,
            ],
            ...
        ];
```

## Translation

- `[VENDOR].[PLUGIN-SLUG]::lang.submenu.label` is the heading of your menu items
- `[VENDOR].[PLUGIN-SLUG]::lang.submenu.[CONTROLLER]` is the your menu item

