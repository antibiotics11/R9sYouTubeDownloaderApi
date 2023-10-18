<?php

namespace Room9Stone\YouTubeDownloader\Api\Http;
use Tnapf\Router\Routing\Methods;

/**
 * API 서버가 직접 처리하는 HTTP 메소드 목록
 *
 * 이 목록에 포함되지 않은 메소드는 사용하지 않는 메소드로 간주한다.
 */
enum Method: string {

  case GET  = Methods::GET;
  case POST = Methods::POST;

  public const ALL = [ Methods::GET, Methods::POST ];

};