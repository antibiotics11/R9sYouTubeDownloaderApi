<?php

namespace Room9Stone\YouTubeDownloader\Api\YouTube\Exception;
use Exception;
use JetBrains\PhpStorm\Immutable;

/**
 * YouTubeException 및 TooManyRequestsException 의 Wrapper 클래스.
 */
#[Immutable]
final class VideoException extends Exception {

  public function __construct(string $message = "", int $code = 0, ?Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }

};