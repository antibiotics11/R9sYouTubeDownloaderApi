<?php

namespace Room9Stone\YouTubeDownloader\Api;
use Stringable;
use stdClass;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Immutable;
use function json_encode;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_UNICODE;
use const JSON_UNESCAPED_SLASHES;

#[Immutable]
final class ApiResponse implements Stringable {

  /**
   * HTTP 200 OK와 함께 사용.
   * 요청 처리에 성공한 경우, data 필드를 포함한다.
   */
  public const STATUS_SUCCESS = "success";

  /**
   * HTTP 400 Bad Request와 함께 사용.
   * 요청 경로 또는 파라미터가 유효하지 않은 경우 응답한다.
   */
  public const STATUS_INVALID_REQUEST = "invalid";

  /**
   * HTTP 403 Forbidden과 함께 사용.
   * 요청에 대한 보안 검사가 실패한 경우 응답한다.
   */
  public const STATUS_FORBIDDEN = "forbidden";

  /**
   * HTTP 409 Conflict와 함께 사용.
   * 유효한 요청이지만 요청 처리에 실패한 경우 응답한다.
   */
  public const STATUS_ERROR = "error";

  /**
   * HTTP 401 Unauthorized와 함께 사용.
   * (인증 구현 예정)
   */
  public const STATUS_UNAUTHORIZED = "unauthorized";


  private function __construct(
    #[ExpectedValues([
      self::STATUS_SUCCESS,
      self::STATUS_INVALID_REQUEST,
      self::STATUS_FORBIDDEN,
      self::STATUS_ERROR,
      self::STATUS_UNAUTHORIZED
    ])]
    private readonly string  $status,
    private readonly ?array  $data,
    private readonly ?string $message
  ) {}

  /**
   * @param array|stdClass|null $data
   * @return self
   */
  public static function success(array|stdClass|null $data = null): self {
    if ($data instanceof stdClass) {
      $data = (array)$data;
    }
    return new self(self::STATUS_SUCCESS, $data, null);
  }

  /**
   * @param string $message
   * @return self
   */
  public static function invalid(string $message = ""): self {
    return new self(self::STATUS_INVALID_REQUEST, null, $message);
  }

  /**
   * @param string $message
   * @return self
   */
  public static function forbidden(string $message = ""): self {
    return new self(self::STATUS_FORBIDDEN, null, $message);
  }

  /**
   * @param string $message
   * @return self
   */
  public static function error(string $message = ""): self {
    return new self(self::STATUS_ERROR, null, $message);
  }

  /**
   * @param string $message
   * @return self
   */
  public static function unauthorized(string $message = ""): self {
    return new self(self::STATUS_UNAUTHORIZED, null, $message);
  }

  public function getStatus(): string {
    return $this->status;
  }

  public function getData(): ?array {
    return $this->data;
  }

  public function getMessage(): ?string {
    return $this->message;
  }

  public function __toString(): string {
    return json_encode([
      "status"  => $this->status,
      "data"    => $this->data,
      "message" => $this->message
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  }

};