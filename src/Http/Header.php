<?php

namespace Room9Stone\YouTubeDownloader\Api\Http;

/**
 * API 서버가 직접 처리하는 HTTP 헤더 목록
 */
enum Header: string {

  case SERVER                       = "Server";
  case CONTENT_TYPE                 = "Content-Type";
  case EXPIRES                      = "Expires";
  case CACHE_CONTROL                = "Cache-Control";
  case PRAGMA                       = "Pragma";
  case USER_AGENT                   = "User-Agent";
  case X_FORWARDED_FOR              = "X-Forwarded-For";

  case ACCESS_CONTROL_ALLOW_ORIGIN  = "Access-Control-Allow-Origin";
  case ACCESS_CONTROL_ALLOW_METHODS = "Access-Control-Allow-Methods";
  case ACCESS_CONTROL_MAX_AGE       = "Access-Control-Max-Age";

};