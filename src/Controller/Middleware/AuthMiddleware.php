<?php

namespace Room9Stone\YouTubeDownloader\Api\Controller\Middleware;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface;
use Room9Stone\YouTubeDownloader\Api\ApiResponse;
use Tnapf\Router\Interfaces\ControllerInterface;
use Tnapf\Router\Routing\RouteRunner;
use Room9Stone\YouTubeDownloader\Api\Http\Header;
use Room9Stone\YouTubeDownloader\Api\Controller\Middleware\Exception\AuthException;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\ArrayShape;
use function trim;
use function explode;
use function base64_decode;
use function strcmp, strcasecmp;

final class AuthMiddleware implements ControllerInterface {

  public function __construct(
    private readonly ResponseFactoryInterface $responseFactory,
    private readonly StreamFactoryInterface $streamFactory,

    #[Immutable]
    #[ArrayShape([
      "BASIC_ENABLED"   => "boolean",
      "BASIC_ACCOUNTS"  => "array"
    ])]
    private readonly array $authConfig = []

  ) {}

  public function handle(ServerRequestInterface $request, ResponseInterface $response, RouteRunner $route): ResponseInterface {

    $account = null;
    if ($this->authConfig["BASIC_ENABLED"] ?? false) {
      try {
        $account = $this->basic($request);
      } catch (AuthException $e) {
        return $this->responseFactory
          ->createResponse(StatusCodeInterface::STATUS_UNAUTHORIZED)
          ->withHeader(Header::WWW_AUTHENTICATE->value, "Basic realm=\"Api Access\"")
          ->withBody($this->streamFactory->createStream(
            ApiResponse::unauthorized($e->getMessage())
          ));
      }
    }

    if ($account !== null) {
      $route->args->user = $account["user"];
      $route->args->password = $account["password"];
    }

    return $route->next($request, $response);

  }

  /**
   * @param ServerRequestInterface $request
   * @return string[]
   * @throws AuthException
   */
  private function basic(ServerRequestInterface $request): array {

    $authorization = $request->getHeader(Header::AUTHORIZATION->value) ?? null;
    if ($authorization === null) {
      throw new AuthException($authorization, "Authorization required.");
    }
    $authorization = $authorization[0] ?? "";

    @list($type, $credential) = explode(" ", $authorization);
    if ($type === null || $credential === null) {
      throw new AuthException($authorization, "Invalid authorization.");
    }
    if (strcasecmp(trim($type), "basic") != 0) {
      throw new AuthException($authorization, "Invalid type.");
    }

    $credential = base64_decode(trim($credential), true);
    if ($credential === false) {
      throw new AuthException($authorization, "Invalid credential.");
    }

    @list($authUser, $authPassword) = explode(":", $credential);
    if ($authUser === null || $authPassword === null) {
      throw new AuthException($authorization, "Invalid credential.");
    }

    $accounts = $this->authConfig["BASIC_ACCOUNTS"] ?? [];
    $password = $accounts[trim($authUser)] ?? null;

    if ($password === null) {
      throw new AuthException($authorization, "No matching account found.");
    }

    if (strcmp(trim($password), trim($authPassword)) != 0) {
      throw new AuthException($authorization, "No matching account found.");
    }

    return [ "user" => $authUser, "password" => $authPassword ];

  }

};