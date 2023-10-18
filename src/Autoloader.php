<?php

namespace Room9Stone\YouTubeDownloader\Api;
use function spl_autoload_register;
use function sprintf, substr, strlen, str_replace;
use function is_readable;
use const DIRECTORY_SEPARATOR;

class Autoloader {

  public static function register(): bool {
    return spl_autoload_register(function(String $class): void {

      $namespacePrefix = sprintf("%s\\", __NAMESPACE__);
      $classRelativePath = str_replace("\\", "/", substr($class, strlen($namespacePrefix)));
      $classFilePath = sprintf("%s%s%s.php", __DIR__, DIRECTORY_SEPARATOR, $classRelativePath);

      if (is_readable($classFilePath)) {
        require_once($classFilePath);
      }

    });
  }

};