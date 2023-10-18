<?php

namespace Room9Stone\YouTubeDownloader\Api\Http\Message;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\Response;

final class ResponseFactory implements ResponseFactoryInterface {

  /**
   * @param int $code
   * @param string $reasonPhrase
   * @return ResponseInterface
   */
  public function createResponse(int $code = 200, string $reasonPhrase = ""): ResponseInterface {
    return new Response($code, [], "", "1.1", $reasonPhrase);
  }

};