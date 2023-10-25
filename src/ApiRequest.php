<?php

namespace Room9Stone\YouTubeDownloader\Api;
use stdClass;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\ObjectShape;
use InvalidArgumentException;
use function sprintf, trim, is_string, strlen, strtolower;
use function in_array;
use function filter_var;
use function json_decode;
use const FILTER_VALIDATE_URL;

#[Immutable]
final class ApiRequest {

  private const REQUEST_FIELD   = [ "video_id", "video_url", "download_option" ];
  private const DOWNLOAD_OPTION = [ "all", "info", "video", "audio" ];

  private function __construct(
    private readonly ?string $videoId,
    private readonly ?string $videoUrl,
    #[ExpectedValues(self::DOWNLOAD_OPTION)]
    private readonly string $downloadOption = "all"
  ) {}

  /**
   * @param stdClass|string $requestObject
   * @return self
   */
  public static function fromJson(
    #[ObjectShape([
      "video_id"        => "string"|null,
      "video_url"       => "string"|null,
      "download_option" => "string"|null
    ])]
    stdClass|string $requestObject
  ): self {

    if (is_string($requestObject)) {
      $requestObject = json_decode($requestObject);
    }

    return self::fromArray((array)$requestObject);

  }

  /**
   * @param array $requestArray
   * @return self
   * @throws InvalidArgumentException
   */
  public static function fromArray(
    #[ArrayShape([
      "video_id"        => "string"|null,
      "video_url"       => "string"|null,
      "download_option" => "string"|null
    ])]
    array $requestArray
  ): self {

    $tmpArray = [];
    foreach ($requestArray as $field => $value) {

      $field = strtolower(trim($field));
      if (!in_array($field, self::REQUEST_FIELD)) {
        throw new InvalidArgumentException(sprintf("Invalid request field '%s'.", $field));
      }

      if (!is_string($value) && $value !== null) {
        throw new InvalidArgumentException(sprintf("Invalid type for '%s'. It must be a string.", $field));
      }
      $value = trim($value);

      $tmpArray[$field] = strlen($value) == 0 ? null : $value;
    }

    $videoId = $tmpArray[self::REQUEST_FIELD[0]] ?? null;
    $videoUrl = $tmpArray[self::REQUEST_FIELD[1]] ?? null;
    $downloadOption = $tmpArray[self::REQUEST_FIELD[2]] ?? "all";

    if ($videoId === null && $videoUrl === null) {
      throw new InvalidArgumentException("Either 'video_id' or 'video_url' is required.");
    }
    if ($videoUrl !== null && filter_var($videoUrl, FILTER_VALIDATE_URL) === false) {
      throw new InvalidArgumentException("Invalid 'video_url'. It must be a valid URL.");
    }
    if (!in_array($downloadOption, self::DOWNLOAD_OPTION)) {
      throw new InvalidArgumentException("Invalid 'download_option'.");
    }

    return new self($videoId, $videoUrl, $downloadOption);

  }

  public function getVideoId(): ?string {
    return $this->videoId;
  }

  public function getVideoUrl(): ?string {
    return $this->videoUrl;
  }

  public function getDownloadOption(): string {
    return $this->downloadOption;
  }

};