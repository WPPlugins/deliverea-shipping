<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit592d218da487b3ba9a3fd7df59345a96
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Deliverea\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Deliverea\\' => 
        array (
            0 => __DIR__ . '/..' . '/deliverea/deliverea-php/src/Deliverea',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit592d218da487b3ba9a3fd7df59345a96::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit592d218da487b3ba9a3fd7df59345a96::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}