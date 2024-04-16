<?php

namespace antibiotics11\AnsiStyler;

enum AnsiColorCode: int {

  // Foreground colors
  case FOREGROUND_BLACK          = 30;
  case FOREGROUND_RED            = 31;
  case FOREGROUND_GREEN          = 32;
  case FOREGROUND_YELLOW         = 33;
  case FOREGROUND_BLUE           = 34;
  case FOREGROUND_MAGENTA        = 35;
  case FOREGROUND_CYAN           = 36;
  case FOREGROUND_WHITE          = 37;
  case FOREGROUND_DEFAULT        = 39;

  // Bright foreground colors
  case FOREGROUND_BRIGHT_BLACK   = 90;
  case FOREGROUND_BRIGHT_RED     = 91;
  case FOREGROUND_BRIGHT_GREEN   = 92;
  case FOREGROUND_BRIGHT_YELLOW  = 93;
  case FOREGROUND_BRIGHT_BLUE    = 94;
  case FOREGROUND_BRIGHT_MAGENTA = 95;
  case FOREGROUND_BRIGHT_CYAN    = 96;
  case FOREGROUND_BRIGHT_WHITE   = 97;

  // Background colors
  case BACKGROUND_BLACK          = 40;
  case BACKGROUND_RED            = 41;
  case BACKGROUND_GREEN          = 42;
  case BACKGROUND_YELLOW         = 43;
  case BACKGROUND_BLUE           = 44;
  case BACKGROUND_MAGENTA        = 45;
  case BACKGROUND_CYAN           = 46;
  case BACKGROUND_WHITE          = 47;
  case BACKGROUND_DEFAULT        = 49;

  // Bright background colors
  case BACKGROUND_BRIGHT_BLACK   = 100;
  case BACKGROUND_BRIGHT_RED     = 101;
  case BACKGROUND_BRIGHT_GREEN   = 102;
  case BACKGROUND_BRIGHT_YELLOW  = 103;
  case BACKGROUND_BRIGHT_BLUE    = 104;
  case BACKGROUND_BRIGHT_MAGENTA = 105;
  case BACKGROUND_BRIGHT_CYAN    = 106;
  case BACKGROUND_BRIGHT_WHITE   = 107;

}
