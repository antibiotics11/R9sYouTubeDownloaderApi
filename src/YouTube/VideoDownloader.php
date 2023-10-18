<?php

namespace Room9Stone\YouTubeDownloader\Api\YouTube;
use YouTube\Exception\TooManyRequestsException;
use YouTube\Exception\YouTubeException;
use YouTube\YouTubeDownloader;
use YouTube\Utils\Utils;
use Room9Stone\YouTubeDownloader\Api\YouTube\Entity\Video;
use Room9Stone\YouTubeDownloader\Api\YouTube\Entity\VideoDetails;
use Room9Stone\YouTubeDownloader\Api\YouTube\Exception\VideoException;

class VideoDownloader {

  private YouTubeDownloader $youtubeDownloader;
  private VideoCache        $videoCache;

  public function __construct() {
    $this->youtubeDownloader = new YouTubeDownloader();
    $this->videoCache = new VideoCache();
  }

  /**
   * 동영상 URL에서 동영상 ID를 추출한다.
   *
   * @param string $videoUrl
   * @return string
   */
  public static function getVideoId(string $videoUrl): string|false {
    return Utils::extractVideoId($videoUrl);
  }

  /**
   * Video 객체를 반환한다.
   *
   * @param string $videoId
   * @return Video|null
   * @throws VideoException
   */
  public function getVideo(string $videoId): ?Video {

    if (!$this->videoCache->has($videoId)) {
      try {
        $downloadOptions = $this->youtubeDownloader->getDownloadLinks($videoId);
      } catch (TooManyRequestsException|YouTubeException $e) {
        throw new VideoException("Failed to load video.");
      }
      $this->videoCache->set(new Video($downloadOptions));
    }

    return $this->videoCache->get($videoId);

  }

  /**
   * 동영상 정보를 VideoDetails 객체로 반환한다.
   *
   * @param string $videoId
   * @return VideoDetails
   * @throws VideoException
   */
  public function getVideoDetails(string $videoId): VideoDetails {
    return $this->getVideo($videoId)->getVideoDetails();
  }

  /**
   * 다운로드할 수 있는 모든 포맷을 배열로 반환한다.
   *
   * @param string $videoId
   * @return array
   * @throws VideoException
   */
  public function getAllFormats(string $videoId): array {
    return $this->getVideo($videoId)->getAllFormats();
  }

  /**
   * 다운로드할 수 있는 모든 동영상 포맷을 배열로 반환한다.
   *
   * @param string $videoId
   * @return array
   * @throws VideoException
   */
  public function getVideoFormats(string $videoId): array {
    return $this->getVideo($videoId)->getVideoFormats();
  }

  /**
   * 다운로드할 수 있는 모든 오디오 포맷을 배열로 반환한다.
   *
   * @param string $videoId
   * @return array
   * @throws VideoException
   */
  public function getAudioFormats(string $videoId): array {
    return $this->getVideo($videoId)->getAudioFormats();
  }

};