<?php
return [
    'plugin' => [
        'name' => 'Xitara Core',
        'description' => 'Core-Plugin für alle Xitara-Plugins, inkl. Backend Seitenmenü',
        'author' => 'Xitara, Manuel Burghammer',
        'homepage' => 'https://xitara.net',
    ],
    'submenu' => [
        'label' => 'Toolbox 4',
    ],
    'core' => [
        //     'comment' => 'Kommentar',
        //     'created_at' => 'angelegt',
        //     'deleted_at' => 'gelöscht',
        //     'id' => 'ID',
        'mainmenu' => 'Hauptmenü',
        'dashboard' => 'Dashboard',
        'menu' => 'Menü-Sortierung',
        //     'updated_at' => 'aktualisiert',
        //     'userid' => 'User ID',
        //     'username' => 'Benutzername',
    ],
    'config' => [
        'label' => 'Grundeinstellungen',
        'description' => 'Einstellungen global für alle Plugins',
    ],
    'install' => [
        'heading' => 'Installation der Toolbox 4 nicht abgeschlossen',
        'text' => 'Vor Beginn müssen einige Grundeinstellungen abgeschlossen werden.',
        'button' => 'Einstellungen',
    ],
    'custommenu' => [
        'label' => 'Benutzerdefinierte Menüs',
        'name' => 'Name',
        'is_submenu' => 'Im Seitenmenü anzeigen',
        'is_active' => 'Aktiv',
        'links' => 'Links',
        'link' => 'Link',
        'text' => 'Text',
        'is_blank' => 'Link im neuen Fenster/Tab öffnen',
    ],
];
