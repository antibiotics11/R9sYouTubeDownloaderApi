<?php

namespace Room9Stone\YouTubeDownloader\Api\Controller\Middleware;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface;
use Tnapf\Router\Interfaces\ControllerInterface;
use Tnapf\Router\Routing\RouteRunner;

final class AuthMiddleware implements ControllerInterface {

  public function __construct(
    private readonly ResponseFactoryInterface $responseFactory,
    private readonly StreamFactoryInterface $streamFactory
  ) {}

  public function handle(ServerRequestInterface $request, ResponseInterface $response, RouteRunner $route): ResponseInterface {

    /**
     *
     * 사용자 인증 로직 구현 예정.
     *
     */
    return $route->next($request, $response);

  }

};