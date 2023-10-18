<?php

namespace Room9Stone\YouTubeDownloader\Api\YouTube;
use React\Cache\ArrayCache;
use Room9Stone\YouTubeDownloader\Api\YouTube\Entity\Video;
use JetBrains\PhpStorm\Pure;
use JetBrains\PhpStorm\Immutable;
use function min;

class VideoCache {

  private ArrayCache $cache;

  private array $hitCounts;

  #[Immutable]
  private readonly int $defaultTtl;

  #[Immutable]
  private readonly int $maxTtl;

  private function hitCount(string $videoId): int {
    $this->hitCounts[$videoId] ??= 0;
    return ++$this->hitCounts[$videoId];
  }

  #[Pure]
  private function ttl(int $hitCount, int $defaultTtl, int $maxTtl): int {
    return min($defaultTtl * $hitCount, $maxTtl);
  }

  /**
   * @param int $limit 캐시 크기
   * @param int $defaultTtl 캐시 기본 TTL
   * @param int $maxTtl 캐시 최대 TTL
   */
  public function __construct(int $limit = 10000, int $defaultTtl = 3600, int $maxTtl = 3600 * 24 * 7) {
    $this->cache = new ArrayCache($limit);
    $this->hitCounts = [];
    $this->defaultTtl = $defaultTtl;
    $this->maxTtl = $maxTtl;
  }

  /**
   * 동영상 ID와 일치하는 캐시가 있는지 확인한다.
   *
   * @param string $videoId
   * @return bool
   */
  public function has(string $videoId): bool {

    $cacheExists = false;
    $this->cache->has($videoId)->then(function (bool $result) use (&$cacheExists): void {
      $cacheExists = $result;
    });

    return $cacheExists;

  }

  /**
   * 새로운 캐시를 추가한다.
   *
   * @param Video $video
   * @return bool
   */
  public function set(Video $video): bool {

    $videoId = $video->getVideoDetails()->getId();
    $ttl = $this->ttl($this->hitCount($videoId), $this->defaultTtl, $this->maxTtl);

    $cacheSet = false;
    $this->cache->set($videoId, $video, $ttl)->then(function (bool $result) use (&$cacheSet) {
      $cacheSet = $result;
    });

    return $cacheSet;

  }

  /**
   * 동영상 ID와 일치하는 캐시를 가져온다.
   *
   * @param string $videoId
   * @return Video|null
   */
  public function get(string $videoId): ?Video {

    $video = null;
    $this->cache->get($videoId)->then(function (?Video $result) use (&$video): void {
      $video = $result;
    });

    return $video;

  }

  /**
   * 캐시를 모두 삭제한다.
   *
   * @return void
   */
  public function clear(): void {
    $this->hitCounts = [];
    $this->cache->clear();
  }

};