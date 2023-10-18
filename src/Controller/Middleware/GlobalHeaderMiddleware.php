<?php

namespace Room9Stone\YouTubeDownloader\Api\Controller\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tnapf\Router\Interfaces\ControllerInterface;
use Tnapf\Router\Routing\RouteRunner;
use Room9Stone\YouTubeDownloader\Api\Http\Header;
use Room9Stone\YouTubeDownloader\Api\Http\Method;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\ArrayShape;
use function implode;

final class GlobalHeaderMiddleware implements ControllerInterface {

  #[Immutable]
  #[ArrayShape([
    "Server"                       => "string",
    "Access-Control-Allow-Methods" => "string",
    "Access-Control-Allow-Origin"  => "string"
  ])]
  private array $responseHeader;

  public function __construct(
    #[ArrayShape([
      "HOSTNAME"    => "string",
      "APPLICATION" => "string"
    ])]
    array $serverConfig
  ) {
    $this->responseHeader[Header::SERVER->value] = $serverConfig["APPLICATION"];
    $this->responseHeader[Header::ACCESS_CONTROL_ALLOW_METHODS->value] = implode(", ", Method::ALL);
    $this->responseHeader[Header::ACCESS_CONTROL_ALLOW_ORIGIN->value] = $serverConfig["HOSTNAME"];
  }

  /**
   * @param ServerRequestInterface $request
   * @param ResponseInterface $response
   * @param RouteRunner $route
   * @return ResponseInterface
   */
  public function handle(ServerRequestInterface $request, ResponseInterface $response, RouteRunner $route): ResponseInterface {

    return $route->next($request, $response
      ->withHeader(Header::SERVER->value, $this->responseHeader[Header::SERVER->value])
      ->withHeader(Header::CONTENT_TYPE->value, "application/json; charset=UTF-8")
      ->withHeader(Header::CACHE_CONTROL->value, "no-cache, no-store, must-revalidate, max-age=0")
      ->withHeader(Header::PRAGMA->value, "no-cache")
      ->withHeader(Header::ACCESS_CONTROL_MAX_AGE->value, "0")
      ->withHeader(Header::ACCESS_CONTROL_ALLOW_METHODS->value, $this->responseHeader[Header::ACCESS_CONTROL_ALLOW_METHODS->value])
      ->withHeader(Header::ACCESS_CONTROL_ALLOW_ORIGIN->value, $this->responseHeader[Header::ACCESS_CONTROL_ALLOW_ORIGIN->value])
    );

  }

};