<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb0f780fdf04fd6dcc58d6b80293dd2cd
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Ddic\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ddic\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb0f780fdf04fd6dcc58d6b80293dd2cd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb0f780fdf04fd6dcc58d6b80293dd2cd::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
