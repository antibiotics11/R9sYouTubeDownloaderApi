<?php

namespace Room9Stone\YouTubeDownloader\Api\YouTube\Entity;
use Stringable;
use YouTube\Models\VideoDetails as VideoDetailsModel;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;
use function json_encode;
use const JSON_UNESCAPED_UNICODE;

#[Immutable]
final class VideoDetails extends VideoDetailsModel implements Stringable {

  private function __construct(
    #[ArrayShape([
      "videoId"           => "string",
      "title"             => "string",
      "shortDescription"  => "string",
      "author"            => "string",
      "channelId"         => "string",
      "thumbnail"         => [ "thumbnails" => "array" ],
      "lengthSeconds"     => "string",
      "viewCount"         => "string",
      "allowRatings"      => "boolean",
      "isPrivate"         => "boolean",
      "isCrawlable"       => "boolean",
      "isOwnerViewing"    => "boolean",
      "isUnpluggedCorpus" => "boolean",
      "isLiveContent"     => "boolean"
    ])]
    array $videoDetails = []
  ) {
    $this->videoDetails = $videoDetails;
  }

  /**
   * YouTube\Models\VideoDetails 인스턴스를 Room9Stone\YouTubeDownloader\Api\YouTube\Entity\VideoDetails 인스턴스로 변환한다.
   *
   * @param VideoDetailsModel $videoDetails
   * @return self
   */
  public static function fromVideoDetailsModel(VideoDetailsModel $videoDetails): self {
    return new self($videoDetails->videoDetails);
  }

  /**
   * @return array
   */
  public function getVideoDetails(): array {
    return $this->videoDetails;
  }

  /**
   * 동영상 섬네일 목록을 배열로 반환한다.
   *
   * @return array|null
   */
  public function getThumbnails(): ?array {
    return $this->videoDetails["thumbnail"]["thumbnails"] ?? null;
  }

  /**
   * 동영상 업로더 닉네임을 문자열로 반환한다.
   *
   * @return string|null
   */
  public function getAuthor(): ?string {
    return $this->videoDetails["author"] ?? null;
  }

  /**
   * 동영상 업로더 채널 ID를 문자열로 반환한다.
   *
   * @return string|null
   */
  public function getChannelId(): ?string {
    return $this->videoDetails["channelId"] ?? null;
  }

  /**
   * 동영상 전체 길이를 정수 형식의 문자열로 반환한다.
   *
   * @return string|null
   */
  public function getLengthSeconds(): ?string {
    return $this->videoDetails["lengthSeconds"] ?? null;
  }

  /**
   * videoDetails 배열을 JSON 인코딩하여 반환한다.
   *
   * @return string
   */
  public function __toString(): string {
    return json_encode($this->videoDetails, JSON_UNESCAPED_UNICODE);
  }

}