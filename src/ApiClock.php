<?php

namespace Room9Stone\YouTubeDownloader\Api;
use DateTimeInterface, DateTime, DateTimeZone;
use Exception;
use function time;

class ApiClock {

  private static self $clock;
  public static function getClock(): self {
    self::$clock ??= new self();
    return self::$clock;
  }

  protected ?DateTime $dateTime = null;

  protected function initialize(): void {
    $this->dateTime ??= new DateTime("now");
  }

  /**
   * 현재 시간을 지정된 포맷으로 가져온다.
   *
   * @param string $format
   * @return string
   */
  public function now(string $format = "H:i:s"): string {
    $this->initialize();
    return $this->dateTime->setTimestamp(time())->format($format);
  }

  /**
   * 현재 시간을 RFC2822 포맷으로 가져온다.
   *
   * @return string
   */
  public function nowRfc2822(): string {
    return $this->now(DateTimeInterface::RFC2822);
  }

  /**
   * 현재 시간을 RFC3339 포맷으로 가져온다.
   *
   * @return string
   */
  public function nowRfc3339(): string {
    return $this->now(DateTimeInterface::RFC3339);
  }

  /**
   * 전역 타임존을 설정한다.
   *
   * @param string $timezone
   * @return void
   * @throws Exception
   */
  public function setTimezone(string $timezone = "GMT"): void {
    $this->initialize();
    $this->dateTime->setTimezone(new DateTimeZone($timezone));
  }

  /**
   * 전역 타임존을 가져온다.
   *
   * @return string
   */
  public function getTimezone(): string {
    $this->initialize();
    return $this->dateTime->getTimezone()->getName();
  }

  private function __construct() {}
  private function __clone(): void {}

}