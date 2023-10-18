<?php

namespace Room9Stone\YouTubeDownloader\Api\System\Posix;

/**
 * 이 클래스는 아래 소스에서 가져옴.
 * https://github.com/antibiotics11/PosixSignalHandler/releases/tag/v0.3
 */

declare(ticks = 1);
pcntl_async_signals(true);

class PosixSignalHandler {

  protected static array $handlers = [];

  protected static function getSignalValue(PosixSignal|int $signal): int {
    return ($signal instanceof PosixSignal) ? $signal->value : $signal;
  }

  /**
   * Handles the specified signal by invoking registered handlers.
   *
   * @param PosixSignal|int $signal The signal to handle.
   * @return void
   */
  public static function handle(PosixSignal|int $signal): void {

    $signalValue = self::getSignalValue($signal);
    if (!self::handlerExists($signalValue)) {
      return;
    }

    foreach (self::$handlers[$signalValue] as $handler) {
      try {
        call_user_func($handler["handler"], $handler["args"]);
      } catch (Throwable $e) {
        trigger_error($e->getMessage(), E_USER_WARNING);
      }
    }

  }

  /**
   * Adds a handler for the specified signal.
   *
   * @param PosixSignal|int $signal The signal to add a handler for.
   * @param callable $handler The handler function to be invoked.
   * @param array $args Additional arguments to be passed to the handler.
   * @param int|null $order The order in which the handler should be executed.
   * @return int|false The order of the added handler or false on failure.
   */
  public static function addHandler(PosixSignal|int $signal, callable $handler, array $args = [], ?int $order = null): int|false {

    $signalValue = self::getSignalValue($signal);
    if (!isset(self::$handlers[$signalValue])) {
      self::$handlers[$signalValue] = [];
    }

    $order = $order ?? array_key_last(self::$handlers[$signalValue]) ?? -1;
    $order++;

    self::$handlers[$signalValue][$order] = [
      "handler" => $handler,
      "args"    => $args
    ];

    if (!pcntl_signal($signalValue, [ self::class, "handle" ])) {
      return false;
    }
    return $order;

  }

  /**
   * Checks if a handler is registered for the specified signal and order.
   *
   * @param PosixSignal|int $signal The signal to check.
   * @param int|null $order The order of the handler to check.
   * @return bool True if the handler exists, false otherwise.
   */
  public static function handlerExists(PosixSignal|int $signal, ?int $order = null): bool {

    $signalValue = self::getSignalValue($signal);
    if (!isset(self::$handlers[$signalValue])) {
      return false;
    }

    if ($order === null) {
      return count(self::$handlers[$signalValue]) > 0;
    }

    if (isset(self::$handlers[$signalValue][$order])) {
      if (is_callable(self::$handlers[$signalValue][$order]["handler"])) {
        return true;
      }
    }

    return false;

  }

  /**
   * Removes a handler for the specified signal and order.
   *
   * @param PosixSignal|int $signal The signal to remove a handler from.
   * @param int|null $order The order of the handler to remove.
   * @return int|false The order of the removed handler or false on failure.
   */
  public static function removeHandler(PosixSignal|int $signal, ?int $order = null): int|false {

    $signalValue = self::getSignalValue($signal);

    $order ??= array_key_last(self::$handlers[$signalValue]);
    if ($order === null || !self::handlerExists($signalValue, $order)) {
      return false;
    }

    unset(self::$handlers[$signalValue][$order]);

    if (count(self::$handlers[$signalValue]) == 0) {
      unset(self::$handlers[$signalValue]);
      pcntl_signal($signalValue, SIG_DFL);
    }

    return $order;

  }

  /**
   * Retrieves the handlers for the specified signal.
   *
   * @param PosixSignal|int $signal The signal to retrieve handlers for.
   * @return array|null An array of handlers or null if none are registered.
   */
  public static function getHandlers(PosixSignal|int $signal): ?array {
    return self::$handlers[self::getSignalValue($signal)] ?? null;
  }

  /**
   * Clears all handlers for the specified signal.
   *
   * @param PosixSignal|int $signal The signal to clear handlers for.
   * @return void
   */
  public static function clearHandlers(PosixSignal|int $signal): void {

    $signalValue = self::getSignalValue($signal);
    self::$handlers[$signalValue] = [];
    pcntl_signal($signalValue, SIG_DFL);

  }

  /**
   * Clears all handlers for all signals.
   *
   * @return void
   */
  public static function clearAllHandlers(): void {

    foreach (self::$handlers as $signalValue => $handlers) {
      self::clearHandlers($signalValue);
    }
    self::$handlers = [];

  }

  #[JetBrains\PhpStorm\Deprecated(
    reason: "Use handle() instead.",
    replacement: "%class%::handle(%parameter0%)"
  )]
  public static function handleSignal(PosixSignal|int $signal): void {
    self::handle($signal);
  }

  #[JetBrains\PhpStorm\Deprecated(
    reason: "Use addHandler() instead.",
    replacement: "%class%::addHandler(%parameter0%,%parameter1%,%parameter2%,%parameter3%)"
  )]
  public static function register(PosixSignal|int $signal, callable $handler, array $params = [], int $order = -1): int|false {
    return self::addHandler($signal, $handler, $params, $order);
  }

  #[JetBrains\PhpStorm\Deprecated(
    reason: "Use handlerExists() instead.",
    replacement: "%class%::handlerExists(%parameter0%,%parameter1%)"
  )]
  public static function isRegistered(PosixSignal|int $signal, int $order = -1): bool {
    return self::handlerExists($signal, $order);
  }

  #[JetBrains\PhpStorm\Deprecated(
    reason: "Use removeHandler() instead.",
    replacement: "%class%::removeHandler(%parameter0%,%parameter1%)"
  )]
  public static function unregister(PosixSignal|int $signal, int $order = -1): int|false {
    return self::removeHandler($signal, $order);
  }

}
