<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit703c62bfde623bfcc7d99f6aa1a09dab
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'WSAL_Vendor\\WP_Async_Request' => __DIR__ . '/..' . '/classes/wp-async-request.php',
        'WSAL_Vendor\\WP_Background_Process' => __DIR__ . '/..' . '/classes/wp-background-process.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit703c62bfde623bfcc7d99f6aa1a09dab::$classMap;

        }, null, ClassLoader::class);
    }
}
