#!/usr/bin/php8.2
<?php

const PHAR_FILENAME = "r9sapi.phar";
const PHAR_PROJECT_DIR = __DIR__ . "/..";
const PHAR_STUB = <<<STUB
#!/usr/bin/env php
<?php
declare(ticks = 1, strict_types = 1);

posix_getuid() == 0 || die("Error: root 권한으로 실행해야 합니다.\r\n");
(float)PHP_VERSION >= 8.1 || die("Error: PHP 8.1 또는 상위 버전이 필요합니다.\r\n");

require_once "phar://" . __FILE__ . "/vendor/autoload.php";
require_once "phar://" . __FILE__ . "/src/Autoloader.php";

Room9Stone\YouTubeDownloader\Api\Autoloader::register();
(new Room9Stone\YouTubeDownloader\Api\ApiServer(
  Room9Stone\YouTubeDownloader\Api\ApiConfig::toArray()
))->run();

__HALT_COMPILER();
STUB;

function main(int $argc, array $argv): void {

  if (!Phar::canWrite()) {
    shutdown(1, "Phar이 읽기 전용이며 빌드할 수 없습니다.");
  }

  $filename = $argv[1] ?? PHAR_FILENAME;
  $directory = $argv[2] ?? PHAR_PROJECT_DIR;
  $stub = $argv[3] ?? PHAR_STUB;

  if (build($filename, $directory, $stub)) {
    shutdown(0, "빌드 완료!");
  }

  shutdown(1, "빌드 실패했습니다.");

}

function build(string $filename, string $directory, string $stub): bool {

  if (file_exists($filename)) {
    try {
      Phar::unlinkArchive($filename);
    } catch (PharException) {
      return false;
    }
  }

  $phar = new Phar($filename);
  $phar->buildFromDirectory($directory);
  $phar->setStub($stub);
  $phar->setSignatureAlgorithm(Phar::SHA256);
  $phar->compressFiles(Phar::GZ);

  if ($phar->isFileFormat(Phar::PHAR)) {
    chmod($filename, 0707);
    return true;
  }

  return false;

}

function shutdown(int $status = 0, string $message = ""): void {
  if (strlen($message) > 0) {
    printf("%s\r\n", $message);
  }
  exit($status);
}

main($_SERVER["argc"], $_SERVER["argv"]);
