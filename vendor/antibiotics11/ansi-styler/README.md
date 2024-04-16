# ansi-styler
A PHP utility for applying ANSI colors and text styles to console output.

```php
use antibiotics11\AnsiStyler\{AnsiColorCode, AnsiFormatter};

// create a new AnsiFormatter instance
$styler = new AnsiFormatter();

// apply yellow background color, red foreground color and bold style
$formattedText = $styler->withBackgroundColor(AnsiColorCode::BACKGROUND_YELLOW)
                        ->withForegroundColor(AnsiColorCode::FOREGROUND_RED)
                        ->withBold()
                        ->format("Hello, World!");

printf("%s\r\n", $formattedText);

// reinitialize the AnsiFormatter
$styler->initialize();
```

## Requirements

- PHP >= 8.1

## Installation

```bash
composer require antibiotics11/ansi-styler
```