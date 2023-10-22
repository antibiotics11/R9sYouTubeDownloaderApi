<?php

namespace Room9Stone\YouTubeDownloader\Api\Controller\Middleware\Exception;
use Exception;
use JetBrains\PhpStorm\Immutable;
use function sprintf;

#[Immutable]
final class AuthException extends Exception {

  public function __construct(private readonly string $authorization, string $details = "", int $code = 0, ?Exception $previous = null) {
    $message = sprintf("Authentication failed with \"%s\": %s", $this->authorization, $details);
    parent::__construct($message, $code, $previous);
  }

  public function getAuthorization(): string {
    return $this->authorization;
  }

}