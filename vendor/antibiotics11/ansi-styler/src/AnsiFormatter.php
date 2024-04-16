<?php

namespace antibiotics11\AnsiStyler;
use function sprintf, trim;

class AnsiFormatter {

  protected int  $backgroundColor;
  protected int  $foregroundColor;
  protected bool $isUnderlined;
  protected bool $isDoublyUnderlined;
  protected bool $isBold;
  protected bool $isItalic;
  protected bool $isBlinking;
  protected bool $shouldTrim;

  public function __construct() {
    $this->initialize();
  }

  public function initialize(): void {
    $this->backgroundColor    = 0;
    $this->foregroundColor    = 0;
    $this->isUnderlined       = false;
    $this->isDoublyUnderlined = false;
    $this->isBold             = false;
    $this->isItalic           = false;
    $this->isBlinking         = false;
    $this->shouldTrim         = false;
  }

  /**
   * Set the background color using an ANSI color code.
   *
   * @param int|AnsiColorCode $color
   * @return self
   */
  public function withBackgroundColor(int|AnsiColorCode $color): self {
    $color instanceof AnsiColorCode and $color = $color->value;
    $this->backgroundColor = $color;
    return $this;
  }

  /**
   * Set the foreground color using an ANSI color code.
   *
   * @param int|AnsiColorCode $color
   * @return self
   */
  public function withForegroundColor(int|AnsiColorCode $color): self {
    $color instanceof AnsiColorCode and $color = $color->value;
    $this->foregroundColor = $color;
    return $this;
  }

  /**
   * Set both foreground and background colors.
   *
   * @param int|AnsiColorCode $foregroundColor
   * @param int|AnsiColorCode $backgroundColor
   * @return self
   */
  public function withColor(int|AnsiColorCode $foregroundColor = 39, int|AnsiColorCode $backgroundColor = 49): self {
    return $this->withForegroundColor($foregroundColor)->withBackgroundColor($backgroundColor);
  }

  /**
   * Enable or disable underline style.
   *
   * @param bool $underline
   * @return self
   */
  public function withUnderline(bool $underline = true): self {
    $this->isUnderlined = $underline;
    return $this;
  }

  /**
   * Enable or disable bold style.
   *
   * @param bool $bold
   * @return self
   */
  public function withBold(bool $bold = true): self {
    $this->isBold = $bold;
    return $this;
  }

  /**
   * Enable or disable italic style.
   *
   * @param bool $italic
   * @return self
   */
  public function withItalic(bool $italic = true): self {
    $this->isItalic = $italic;
    return $this;
  }

  /**
   * Enable or disable trimming.
   *
   * @param bool $trim
   * @return self
   */
  public function trim(bool $trim = true): self {
    $this->shouldTrim = $trim;
    return $this;
  }

  /**
   * Generate the formatted string for the given expression.
   *
   * @param string $expression
   * @return string
   */
  public function format(string $expression): string {

    $escapeSequence = "";
    $this->isBold          and $escapeSequence .= "\033[1m";
    $this->isItalic        and $escapeSequence .= "\033[3m";
    $this->isUnderlined    and $escapeSequence .= "\033[4m";
    $this->foregroundColor and $escapeSequence .= sprintf("\033[%dm", $this->foregroundColor);
    $this->backgroundColor and $escapeSequence .= sprintf("\033[%dm", $this->backgroundColor);
    $this->shouldTrim      and $expression = trim($expression);

    return $escapeSequence . $expression . "\033[0m";

  }

}