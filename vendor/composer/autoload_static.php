<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5731d70f7daba58d690523ab2395b731
{
    public static $files = array (
        'ad155f8f1cf0d418fe49e248db8c661b' => __DIR__ . '/..' . '/react/promise/src/functions_include.php',
        'ebf8799635f67b5d7248946fe2154f4a' => __DIR__ . '/..' . '/ringcentral/psr7/src/functions_include.php',
        'c4e03ecd470d2a87804979c0a8152284' => __DIR__ . '/..' . '/react/async/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Y' => 
        array (
            'YouTube\\' => 8,
        ),
        'T' => 
        array (
            'Tnapf\\Router\\' => 13,
            'Tests\\Tnapf\\Router\\' => 19,
            'Tests\\CommandString\\Utils\\' => 26,
        ),
        'R' => 
        array (
            'RingCentral\\Psr7\\' => 17,
            'React\\Stream\\' => 13,
            'React\\Socket\\' => 13,
            'React\\Promise\\' => 14,
            'React\\Http\\' => 11,
            'React\\EventLoop\\' => 16,
            'React\\Dns\\' => 10,
            'React\\Cache\\' => 12,
            'React\\Async\\' => 12,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
        ),
        'J' => 
        array (
            'JetBrains\\PhpStorm\\' => 19,
        ),
        'H' => 
        array (
            'HttpSoft\\ServerRequest\\' => 23,
            'HttpSoft\\Response\\' => 18,
            'HttpSoft\\Message\\' => 17,
            'HttpSoft\\Emitter\\' => 17,
        ),
        'F' => 
        array (
            'Fig\\Http\\Message\\' => 17,
        ),
        'E' => 
        array (
            'Evenement\\' => 10,
        ),
        'C' => 
        array (
            'Curl\\' => 5,
            'CurlDownloader\\' => 15,
            'CommandString\\Utils\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'YouTube\\' => 
        array (
            0 => __DIR__ . '/..' . '/athlon1600/youtube-downloader/src',
        ),
        'Tnapf\\Router\\' => 
        array (
            0 => __DIR__ . '/..' . '/tnapf/router/src',
        ),
        'Tests\\Tnapf\\Router\\' => 
        array (
            0 => __DIR__ . '/..' . '/tnapf/router/tests',
        ),
        'Tests\\CommandString\\Utils\\' => 
        array (
            0 => __DIR__ . '/..' . '/commandstring/utils/tests',
        ),
        'RingCentral\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/ringcentral/psr7/src',
        ),
        'React\\Stream\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/stream/src',
        ),
        'React\\Socket\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/socket/src',
        ),
        'React\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/promise/src',
        ),
        'React\\Http\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/http/src',
        ),
        'React\\EventLoop\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/event-loop/src',
        ),
        'React\\Dns\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/dns/src',
        ),
        'React\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/cache/src',
        ),
        'React\\Async\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/async/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
            1 => __DIR__ . '/..' . '/psr/http-factory/src',
        ),
        'JetBrains\\PhpStorm\\' => 
        array (
            0 => __DIR__ . '/..' . '/jetbrains/phpstorm-attributes/src',
        ),
        'HttpSoft\\ServerRequest\\' => 
        array (
            0 => __DIR__ . '/..' . '/httpsoft/http-server-request/src',
        ),
        'HttpSoft\\Response\\' => 
        array (
            0 => __DIR__ . '/..' . '/httpsoft/http-response/src',
        ),
        'HttpSoft\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/httpsoft/http-message/src',
        ),
        'HttpSoft\\Emitter\\' => 
        array (
            0 => __DIR__ . '/..' . '/httpsoft/http-emitter/src',
        ),
        'Fig\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/fig/http-message-util/src',
        ),
        'Evenement\\' => 
        array (
            0 => __DIR__ . '/..' . '/evenement/evenement/src',
        ),
        'Curl\\' => 
        array (
            0 => __DIR__ . '/..' . '/athlon1600/php-curl-client/src',
        ),
        'CurlDownloader\\' => 
        array (
            0 => __DIR__ . '/..' . '/athlon1600/php-curl-file-downloader/src',
        ),
        'CommandString\\Utils\\' => 
        array (
            0 => __DIR__ . '/..' . '/commandstring/utils/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5731d70f7daba58d690523ab2395b731::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5731d70f7daba58d690523ab2395b731::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5731d70f7daba58d690523ab2395b731::$classMap;

        }, null, ClassLoader::class);
    }
}
