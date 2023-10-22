<?php

namespace Room9Stone\YouTubeDownloader\Api\Controller\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface;
use Tnapf\Router\Interfaces\ControllerInterface;
use Tnapf\Router\Routing\RouteRunner;
use Room9Stone\YouTubeDownloader\Api\ApiLogger;
use Room9Stone\YouTubeDownloader\Api\Http\Header;
use function sprintf, strlen;

final class LogMiddleware implements ControllerInterface {

  public function handle(ServerRequestInterface $request, ResponseInterface $response, RouteRunner $route): ResponseInterface {

    $requestProtocol = $request->getProtocolVersion();
    $requestMethod   = $request->getMethod();
    $requestPath     = $request->getUri()->getPath();
    $requestHeaders  = $request->getHeaders();
    $remoteAddress   = $requestHeaders[Header::X_FORWARDED_FOR->value][0] ?? $request->getServerParams()["REMOTE_ADDR"];
    $remoteUser      = $route->args->user ?? "-";
    $userAgent       = $requestHeaders[Header::USER_AGENT->value][0] ?? "-";

    $responseCode    = $response->getStatusCode();
    $responseSize    = strlen($response->getBody());

    $expression = sprintf("%s - %s \"%s %s HTTP/%s\" %d %d \"%s\"",
      $remoteAddress, $remoteUser,
      $requestMethod, $requestPath, $requestProtocol,
      $responseCode, $responseSize,
      $userAgent
    );

    $logType = match ($responseCode) {
      StatusCodeInterface::STATUS_OK => "notice",
      default => "warning"
    };

    ApiLogger::getInstance()->write($expression, $logType);

    return $route->next($request, $response);

  }

};