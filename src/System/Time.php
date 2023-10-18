<?php

namespace Room9Stone\YouTubeDownloader\Api\System;

/**
 * 이 클래스는 아래 소스에서 가져옴.
 * https://github.com/antibiotics11/simserver/blob/ed383bcaec6442a3130ad3851a2f18e64c94ee8c/src/System/Time.php
 */
class Time {

  public static function setTimeZone(string $timezone = "GMT"): void {
    date_default_timezone_set($timezone);
  }

  public static function getTimeZone(): string {
    return date_default_timezone_get();
  }

  public static function DateYMD(string $separator = "-", ?int $timestamp = null): string {
    return date(sprintf("Y%sm%sd", $separator, $separator), $timestamp ?? time());
  }

  // Formats given timestamp as a date string in RFC2822 format. (current time in default)
  public static function DateRFC2822(?int $timestamp = null): string {
    return date(DATE_RFC2822, $timestamp ?? time());
  }

  // Formats given timestamp as a date string in RFC7231 format. (current time in default)
  public static function DateRFC7231(?int $timestamp = null): string {
    return date(DATE_RFC7231, $timestamp ?? time());
  }

  // Formats given timestamp as a date string in HTTP Cookie format.
  public static function DateCookie(int $timestamp): string {
    return date(DATE_COOKIE, $timestamp);
  }

};