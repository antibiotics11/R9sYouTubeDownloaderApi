<?php

namespace Room9Stone\YouTubeDownloader\Api;
use stdClass;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\ObjectShape;
use InvalidArgumentException;
use function trim, is_string, strtolower;
use function filter_var;
use function in_array;
use function json_decode;
use const FILTER_VALIDATE_URL;

#[Immutable]
final class ApiRequest {

  private function __construct(
    private readonly ?string $videoId,
    private readonly ?string $videoUrl,
    #[ExpectedValues([ "all", "info", "video", "audio" ])]
    private readonly string $downloadOption = "all"
  ) {}

  /**
   * @param stdClass|string $requestJson
   * @return self
   * @throws InvalidArgumentException
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

    $videoId = $requestArray["video_id"] ?? null;
    $videoUrl = $requestArray["video_url"] ?? null;
    $downloadOption = $requestArray["download_option"] ?? "all";
    $downloadOption = strtolower(trim($downloadOption));

    if ($videoId === null && $videoUrl === null) {
      throw new InvalidArgumentException("Either 'video_id' or 'video_url' is required.");
    }
    if ($videoId !== null && !is_string($videoId)) {
      throw new InvalidArgumentException("Invalid type for 'video_id'. It must be a string.");
    } else if ($videoUrl !== null &&
      (!is_string($videoUrl) || filter_var($videoUrl, FILTER_VALIDATE_URL) === false)
    ) {
      throw new InvalidArgumentException("Invalid 'video_url'. It must be a string of valid URL.");
    }
    if (!in_array($downloadOption, [ "all", "info", "video", "audio" ])) {
      throw new InvalidArgumentException("Invalid 'download_option'. It must be either 'all', 'info', 'video', or 'audio'.");
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