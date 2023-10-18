<?php

namespace Room9Stone\YouTubeDownloader\Api\Controller;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface;
use Tnapf\Router\Interfaces\ControllerInterface;
use Tnapf\Router\Routing\RouteRunner;
use Room9Stone\YouTubeDownloader\Api\ApiResponse;

final class DefaultController implements ControllerInterface {

  /**
   * @param ResponseFactoryInterface $responseFactory
   * @param StreamFactoryInterface $streamFactory
   */
  public function __construct(
    private readonly ResponseFactoryInterface $responseFactory,
    private readonly StreamFactoryInterface $streamFactory
  ) {}

  /**
   * @param ServerRequestInterface $request
   * @param ResponseInterface $response
   * @param RouteRunner $route
   * @return ResponseInterface
   */
  public function handle(ServerRequestInterface $request, ResponseInterface $response, RouteRunner $route): ResponseInterface {

    $responseBody = $this->streamFactory->createStream(
      ApiResponse::invalid("Unsupported request for this path.")
    );
    $response = $this->responseFactory
      ->createResponse(StatusCodeInterface::STATUS_BAD_REQUEST)
      ->withBody($responseBody);

    return $route->next($request, $response);

  }

};