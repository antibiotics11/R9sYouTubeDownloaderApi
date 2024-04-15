<?php

namespace Room9Stone\YouTubeDownloader\Api;
use RuntimeException;
use function spl_autoload_register;
use function strlen, strncmp, substr, str_replace, sprintf;
use function is_readable;
use const DIRECTORY_SEPARATOR;

class Autoloader {

  protected const NAMESPACE_PREFIX    = __NAMESPACE__;
  protected const BASE_DIRECTORY      = __DIR__;
  protected const DIRECTORY_SEPARATOR = DIRECTORY_SEPARATOR;

  public static function register(): void {
    if (!spl_autoload_register([ self::class, "loadClass" ])) {
      throw new RuntimeException("Autoloader registration failed.");
    }
  }

  protected static function loadClass(string $class): void {

    $namespacePrefix = self::NAMESPACE_PREFIX;
    $namespaceLength = strlen($namespacePrefix);

    if (strncmp($namespacePrefix, $class, $namespaceLength) !== 0) {
      return;
    }

    $relativeClass = substr($class, $namespaceLength);
    $classFilePath = sprintf("%s%s.php",
      self::BASE_DIRECTORY,
      str_replace("\\", self::DIRECTORY_SEPARATOR, $relativeClass)
    );

    if (!is_readable($classFilePath)) {
      throw new RuntimeException("File not found or not readable at %s.", $classFilePath);
    }

    require_once($classFilePath);

  }

  private function __construct() {}

}