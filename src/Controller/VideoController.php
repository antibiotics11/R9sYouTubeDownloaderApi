<?php

namespace Room9Stone\YouTubeDownloader\Api\Controller;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface;
use Tnapf\Router\Interfaces\ControllerInterface;
use Tnapf\Router\Routing\RouteRunner;
use Room9Stone\YouTubeDownloader\Api\Http\Method;
use Room9Stone\YouTubeDownloader\Api\ApiRequest;
use Room9Stone\YouTubeDownloader\Api\ApiResponse;
use Room9Stone\YouTubeDownloader\Api\YouTube\VideoDownloader;
use Room9Stone\YouTubeDownloader\Api\YouTube\Exception\VideoException;
use InvalidArgumentException;

/**
 * 유튜브 동영상 관련 요청을 처리하는 컨트롤러.
 */
final class VideoController implements ControllerInterface {

  public function __construct(
    private readonly ResponseFactoryInterface $responseFactory,
    private readonly StreamFactoryInterface $streamFactory,
    private readonly VideoDownloader $videoDownloader
  ) {}

  public function handle(ServerRequestInterface $request, ResponseInterface $response, RouteRunner $route): ResponseInterface {

    $requestMethod = Method::tryFrom($request->getMethod());
    $apiRequest = null;
    $responseBody = null;
    $responseCode = StatusCodeInterface::STATUS_CONFLICT;

    try {

      if ($requestMethod === Method::GET) {
        $apiRequest = ApiRequest::fromArray([
          "video_id" => $route->getParameter("id"),
          "download_option" => $route->getParameter("type")
        ]);
      } else if ($requestMethod === Method::POST) {
        $apiRequest = ApiRequest::fromJson($request->getBody()->getContents());
      } else {

        // 요청 메소드가 GET 또는 POST 아니라면
        $responseBody = $this->streamFactory->createStream(
          ApiResponse::forbidden("HTTP method not allowed.")
        );

      }

    } catch (InvalidArgumentException $e) {

      // API 요청이 유효한 형식이 아니라면
      $responseBody = $this->streamFactory->createStream(
        ApiResponse::invalid($e->getMessage())
      );

    }

    if ($apiRequest instanceof ApiRequest) {
      try {

        $videoId = $apiRequest->getVideoId() ?? $apiRequest->getVideoUrl();
        $videoId = VideoDownloader::getVideoId($videoId);
        if ($videoId === false) {
          throw new VideoException("Invalid 'video_id'.");
        }
        $video = $this->videoDownloader->getVideo($videoId);
        $data = match ($apiRequest->getDownloadOption()) {
          "all"   => $video->getAllFormats(),
          "info"  => $video->getVideoDetails()->getVideoDetails(),
          "video" => $video->getVideoFormats(),
          "audio" => $video->getAudioFormats(),
        };

        $responseCode = StatusCodeInterface::STATUS_OK;
        $responseBody = $this->streamFactory->createStream(
          ApiResponse::success($data)
        );

      } catch (VideoException $e) {

        // 동영상 처리중 오류가 발생했다면
        $responseCode = StatusCodeInterface::STATUS_CONFLICT;
        $responseBody = $this->streamFactory->createStream(
          ApiResponse::error($e->getMessage())
        );

      }
    }

    $response = $this->responseFactory
      ->createResponse($responseCode)
      ->withBody($responseBody);

    return $route->next($request, $response);

  }

};