<?php
return [
    /*

    |--------------------------------------------------------------------------
    | Upload dir

    |--------------------------------------------------------------------------
    |
    | The dir where to store the images (relative from public)
    |
    */
    'dir' => 'files',


    /*

    |--------------------------------------------------------------------------
    | Filesystem disks (Flysytem)

    |--------------------------------------------------------------------------
    |
    | Define an array of Filesystem disks, which use Flysystem.
    | You can set extra options, example:
    |
    | 'my-disk' => [
    |        'URL' => url('to/disk'),
    |        'alias' => 'Local storage',
    |    ]
    */
//    'disks' => [
//
//    ],
    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the elFinder routes.
    |
    */
    'route' => [
        'prefix' => 'elfinder',
        'middleware' => 'auth.admin', //Set to null to disable
    ],
    /*
    |--------------------------------------------------------------------------
    | Access filter
    |--------------------------------------------------------------------------
    |
    | Filter callback to check the files
    |
    */
    'access' => 'Barryvdh\Elfinder\Elfinder::checkAccess',

    /*
    |--------------------------------------------------------------------------
    | Roots
    |--------------------------------------------------------------------------
    |
    | By default, the roots file is LocalFileSystem, with the
    above public dir.
    | If you want custom options, you can set your own roots
    below.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | These options are merged, together with 'roots' and passed
    to the Connector.
    | See
    https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1
    |
    */
    'options' => array(
        'roots'  => array(
            array(
                'driver' => 'LocalFileSystem',
                'path'   => public_path().'/images/files',
                'URL'    => 'http://allfor2.com/images/files'
            ),
        )
    ),
];