<?php

namespace Room9Stone\YouTubeDownloader\Api;
use React\Stream\WritableResourceStream;
use antibiotics11\AnsiStyler\{AnsiColorCode, AnsiFormatter};
use JetBrains\PhpStorm\ExpectedValues;
use function sprintf;
use function fopen;

final class ApiLogger {

  private static ?self $instance = null;

  /**
   * @param string|null $console 로그를 출력할 콘솔 장치
   * @param string|null $file    로그를 저장할 파일
   */
  public static function getInstance(?string $console = null, ?string $file = null): self {
    self::$instance ??= new self($console, $file);
    return self::$instance;
  }


  private AnsiFormatter           $ansiStyler;
  private ?WritableResourceStream $consoleStream;
  private ?WritableResourceStream $fileStream;

  private function __construct(?string $console = null, ?string $file = null) {
    $this->ansiStyler    = new AnsiFormatter();
    $this->consoleStream = $console === null ? null : $this->createStream($console);
    $this->fileStream    = $file    === null ? null : $this->createStream($file);
  }

  /**
   * @param string $path
   * @return resource|null
   */
  private function createStream(string $path): WritableResourceStream {
    return new WritableResourceStream(@fopen($path, "a"));
  }

  /**
   * @param string $expression
   * @param string $logType
   * @return void
   */
  public function write(
    string $expression,
    #[ExpectedValues([ "notice", "warning", "error" ])]
    string $logType = "notice"
  ): void {

    $logColor = match ($logType) {
      "notice"  => AnsiColorCode::FOREGROUND_BLUE,
      "warning" => AnsiColorCode::FOREGROUND_YELLOW,
      "error"   => AnsiColorCode::FOREGROUND_RED,
      default   => AnsiColorCode::FOREGROUND_DEFAULT
    };

    $formattedLog = sprintf("[%s] %s\r\n", ApiClock::getClock()->nowRfc2822(), $expression);
    $formattedLog = $this->ansiStyler->withForegroundColor($logColor)->format($formattedLog);
    $this->ansiStyler->initialize();

    if ($this->consoleStream !== null && $this->consoleStream->isWritable()) {
      $this->consoleStream->write($formattedLog);
    }
    if ($this->fileStream !== null && $this->fileStream->isWritable()) {
      $this->fileStream->write($formattedLog);
    }

  }

  /**
   * @return void
   */
  public function close(): void {
    $this->consoleStream?->close();
    $this->fileStream   ?->close();
  }

  private function __clone(): void {}

}