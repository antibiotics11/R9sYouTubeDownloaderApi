<?php

namespace Room9Stone\YouTubeDownloader\Api\System\Console;

/**
 * 이 클래스는 아래 소스에서 가져옴.
 * https://github.com/antibiotics11/EchoLog/releases/tag/v1.2
 */

enum AnsiColorCode: int {

  // Foreground colors
  case FOREGROUND_COLOR_BLACK          = 30;
  case FOREGROUND_COLOR_RED            = 31;
  case FOREGROUND_COLOR_GREEN          = 32;
  case FOREGROUND_COLOR_YELLOW         = 33;
  case FOREGROUND_COLOR_BLUE           = 34;
  case FOREGROUND_COLOR_MAGENTA        = 35;
  case FOREGROUND_COLOR_CYAN           = 36;
  case FOREGROUND_COLOR_WHITE          = 37;
  case FOREGROUND_COLOR_DEFAULT        = 39;

  // Bright foreground colors
  case FOREGROUND_COLOR_BRIGHT_BLACK   = 90;
  case FOREGROUND_COLOR_BRIGHT_RED     = 91;
  case FOREGROUND_COLOR_BRIGHT_GREEN   = 92;
  case FOREGROUND_COLOR_BRIGHT_YELLOW  = 93;
  case FOREGROUND_COLOR_BRIGHT_BLUE    = 94;
  case FOREGROUND_COLOR_BRIGHT_MAGENTA = 95;
  case FOREGROUND_COLOR_BRIGHT_CYAN    = 96;
  case FOREGROUND_COLOR_BRIGHT_WHITE   = 97;

  // Background colors
  case BACKGROUND_COLOR_BLACK          = 40;
  case BACKGROUND_COLOR_RED            = 41;
  case BACKGROUND_COLOR_GREEN          = 42;
  case BACKGROUND_COLOR_YELLOW         = 43;
  case BACKGROUND_COLOR_BLUE           = 44;
  case BACKGROUND_COLOR_MAGENTA        = 45;
  case BACKGROUND_COLOR_CYAN           = 46;
  case BACKGROUND_COLOR_WHITE          = 47;
  case BACKGROUND_COLOR_DEFAULT        = 49;

  // Bright background colors
  case BACKGROUND_COLOR_BRIGHT_BLACK   = 100;
  case BACKGROUND_COLOR_BRIGHT_RED     = 101;
  case BACKGROUND_COLOR_BRIGHT_GREEN   = 102;
  case BACKGROUND_COLOR_BRIGHT_YELLOW  = 103;
  case BACKGROUND_COLOR_BRIGHT_BLUE    = 104;
  case BACKGROUND_COLOR_BRIGHT_MAGENTA = 105;
  case BACKGROUND_COLOR_BRIGHT_CYAN    = 106;
  case BACKGROUND_COLOR_BRIGHT_WHITE   = 107;

};