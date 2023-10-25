<?php

namespace Room9Stone\YouTubeDownloader\Api;
use ReflectionClass;

class ApiConfig {

  /**
   * @const string API 서버가 리스닝할 주소
   */
  public const ADDRESS = "127.0.0.1:80";

  /**
   * @const string API 서버 도메인 네임
   */
  public const HOSTNAME = "localhost";

  /**
   * @const string API 서버의 소프트웨어 이름
   */
  public const APPLICATION = "R9sApi";

  /**
   * @const string API 서버의 소프트웨어 버전 정보
   */
  public const VERSION  = "1.1.1";

  /**
   * @const string API 서버의 전역 타임존
   */
  public const TIMEZONE = "Asia/Seoul";

  /**
   * @const string 로그를 출력할 콘솔
   */
  public const LOG_TERMINAL = "/dev/tty";

  /**
   * @const string 로그를 저장할 파일 경로
   */
  public const LOG_FILE = "/var/log/r9sapi.log";

  /**
   * @const mixed[] API 접근 인증 설정
   */
  public const AUTH_CONFIG = [

    /**
     * @const bool Basic 인증 활성화 여부
     */
    "BASIC_ENABLED" => false,

    /**
     * @const string[] Basic 인증 사용자 계정 목록
     */
    "BASIC_ACCOUNTS" => [
      "test" => "1234"
    ]

  ];


  /**
   * @return array
   */
  public static function toArray(): array {
    return (new ReflectionClass(self::class))->getConstants();
  }

  private function __construct() {}

};