<?php
return array(
    'modules' => array(
        'DoctrineModule',
        'DoctrineORMModule',
        'DoctrineDataFixtureModule',
        'AsseticBundle',
        'ZfcBase',
        'PlaygroundCore',
        'PlaygroundDesign',
        'PlaygroundCms'
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            '../../../config/autoload/{,*.}{global,local,testing}.php',
            './config/{,*.}{testing}.php',
        ),
        'module_paths' => array(
            'module',
            'vendor',
        ),
    ),
);
