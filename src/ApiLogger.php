<?php

namespace Room9Stone\YouTubeDownloader\Api;
use React\Stream\WritableResourceStream;
use Room9Stone\YouTubeDownloader\Api\System\Time;
use ErrorException;
use antibiotics11\AnsiStyler\AnsiColorCode;
use antibiotics11\AnsiStyler\AnsiFormatter as AnsiStyler;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\ArrayShape;
use function sprintf;
use function error_get_last;
use function fopen;
use const PHP_EOL;

class ApiLogger {

  private static ?self $instance = null;

  /**
   * @param string|null $console 로그를 출력할 콘솔
   * @param string|null $file 로그를 저장할 파일
   */
  public static function getInstance(?string $console = null, ?string $file = null): self {
    if (self::$instance === null) {
      self::$instance = new self($console, $file);
    }
    return self::$instance;
  }


  private AnsiStyler $ansiStyler;

  /**
   * @var array<WritableResourceStream>
   */
  #[ArrayShape([
    "consoleStream" => WritableResourceStream::class|null,
    "fileStream"    => WritableResourceStream::class|null
  ])]
  private array $stream;

  private function __construct(?string $console = null, ?string $file = null) {
    $this->ansiStyler = new AnsiStyler();
    $this->stream["consoleStream"] = $console === null ? null : $this->createStream($console);
    $this->stream["fileStream"] = $file === null ? null : $this->createStream($file);
  }

  /**
   * @param string $path
   * @return resource|null
   * @throws ErrorException
   */
  private function createStream(string $path): WritableResourceStream {

    $handler = @fopen($path, "a");
    if ($handler === false) {
      throw new ErrorException(error_get_last()["message"]);
    }

    return new WritableResourceStream($handler);

  }

  /**
   * @param string $expression
   * @param string $logType
   * @return void
   * @throws ErrorException
   */
  public function write(
    string $expression,
    #[ExpectedValues([ "notice", "warning", "error" ])]
    string $logType = "notice"
  ): void {

    $logColor = match ($logType) {
      "notice"  => AnsiColorCode::FOREGROUND_BLUE,
      "warning" => AnsiColorCode::FOREGROUND_YELLOW,
      "error"   => AnsiColorCode::FOREGROUND_RED
    };
    $log = $this->ansiStyler
      ->withColor($logColor)
      ->format(sprintf("[%s] %s%s", Time::DateRFC2822(), $expression, PHP_EOL));

    foreach ($this->stream as $stream) {
      if ($stream instanceof WritableResourceStream && $stream->isWritable()) {
        if (!$stream->write($log)) {
          throw new ErrorException("Failed to write to stream.");
        }
      }
    }

  }

  /**
   * @return void
   */
  public function close(): void {

    foreach ($this->stream as $stream) {
      if ($stream instanceof WritableResourceStream && $stream->isWritable()) {
        $stream->close();
      }
    }

  }

};