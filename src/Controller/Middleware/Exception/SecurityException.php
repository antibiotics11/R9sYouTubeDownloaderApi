<?php

namespace Room9Stone\YouTubeDownloader\Api\Controller\Middleware\Exception;
use Stringable;
use Exception;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class SecurityException extends Exception implements Stringable {

  public function __construct(private readonly string $reason = "", int $code = 0, ?Exception $previous = null) {
    parent::__construct($reason, $code, $previous);
  }

  public function __toString(): string {
    return $this->reason;
  }

};