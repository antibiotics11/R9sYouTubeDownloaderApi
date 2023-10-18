<?php

namespace Room9Stone\YouTubeDownloader\Api\YouTube\Entity;
use YouTube\DownloadOptions;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class Video {

  private readonly VideoDetails $videoDetails;
  private readonly array $allFormats;
  private readonly array $videoFormats;
  private readonly array $audioFormats;

  /**
   * @param DownloadOptions $downloadOptions
   */
  public function __construct(DownloadOptions $downloadOptions) {
    $this->videoDetails = VideoDetails::fromVideoDetailsModel($downloadOptions->getInfo());
    $this->allFormats   = $downloadOptions->getAllFormats();
    $this->videoFormats = $downloadOptions->getVideoFormats();
    $this->audioFormats = $downloadOptions->getAudioFormats();
  }

  /**
   * @return VideoDetails
   */
  public function getVideoDetails(): VideoDetails {
    return $this->videoDetails;
  }

  /**
   * @return array
   */
  public function getAllFormats(): array {
    return $this->allFormats;
  }

  /**
   * @return array
   */
  public function getVideoFormats(): array {
    return $this->videoFormats;
  }

  /**
   * @return array
   */
  public function getAudioFormats(): array {
    return $this->audioFormats;
  }

};