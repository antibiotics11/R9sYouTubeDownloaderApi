<?php

namespace Room9Stone\YouTubeDownloader\Api\Controller\Middleware;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface;
use Tnapf\Router\Interfaces\ControllerInterface;
use Tnapf\Router\Routing\RouteRunner;
use Room9Stone\YouTubeDownloader\Api\Http\Method;
use Room9Stone\YouTubeDownloader\Api\ApiResponse;
use Room9Stone\YouTubeDownloader\Api\Controller\Middleware\Exception\SecurityException;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\ArrayShape;
use function strlen, strcasecmp;
use function in_array;

final class SecurityMiddleware implements ControllerInterface {

  /**
   * @param ResponseFactoryInterface $responseFactory
   * @param StreamFactoryInterface $streamFactory
   * @param array $serverConfig
   */
  public function __construct(
    private readonly ResponseFactoryInterface $responseFactory,
    private readonly StreamFactoryInterface $streamFactory,

    #[Immutable]
    #[ArrayShape([
      "HOSTNAME" => "string",
    ])]
    private readonly array $serverConfig

  ) {}

  /**
   * @param ServerRequestInterface $request
   * @param ResponseInterface $response
   * @param RouteRunner $route
   * @return ResponseInterface
   */
  public function handle(ServerRequestInterface $request, ResponseInterface $response, RouteRunner $route): ResponseInterface {

    try {
      $this->check($request);
    } catch (SecurityException $e) {                         // 보안 검사를 통과하지 못했다면
      $responseBody = $this->streamFactory->createStream(
        ApiResponse::forbidden($e->getMessage())
      );
      return $this->responseFactory
        ->createResponse(StatusCodeInterface::STATUS_FORBIDDEN)
        ->withBody($responseBody);
    }

    return $route->next($request, $response);

  }

  /**
   * @throws SecurityException
   */
  private function check(ServerRequestInterface $request): void {

    if (strcasecmp($request->getUri()->getHost(), $this->serverConfig["HOSTNAME"]) != 0) {
      throw new SecurityException("Invalid Hostname.");
    }

    if (!in_array($request->getMethod(), Method::ALL)) {
      throw new SecurityException("HTTP method not allowed.");
    }

    if (strlen($request->getUri()->getQuery()) > 128) {
      throw new SecurityException("Query string length exceeds the maximum allowed.");
    }

  }

};