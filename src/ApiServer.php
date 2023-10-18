<?php

namespace Room9Stone\YouTubeDownloader\Api;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tnapf\Router\Router;
use React\Socket\SocketServer;
use React\Http\HttpServer;
use React\Http\Middleware\LimitConcurrentRequestsMiddleware;
use React\Http\Middleware\RequestBodyBufferMiddleware;
use React\Http\Middleware\RequestBodyParserMiddleware;
use React\Http\Middleware\StreamingRequestMiddleware;
use HttpSoft\Message\StreamFactory;
use Room9Stone\YouTubeDownloader\Api\YouTube\VideoDownloader;
use Room9Stone\YouTubeDownloader\Api\Http\Message\ResponseFactory;
use Room9Stone\YouTubeDownloader\Api\Controller\Middleware\AuthMiddleware;
use Room9Stone\YouTubeDownloader\Api\Controller\Middleware\GlobalHeaderMiddleware;
use Room9Stone\YouTubeDownloader\Api\Controller\Middleware\LogMiddleware;
use Room9Stone\YouTubeDownloader\Api\Controller\Middleware\SecurityMiddleware;
use Room9Stone\YouTubeDownloader\Api\Controller\DefaultController;
use Room9Stone\YouTubeDownloader\Api\Controller\VideoController;
use Room9Stone\YouTubeDownloader\Api\System\Posix\PosixSignal;
use Room9Stone\YouTubeDownloader\Api\System\Posix\PosixSignalHandler;
use Room9Stone\YouTubeDownloader\Api\System\Time;
use ErrorException;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\NoReturn;
use function sprintf;

class ApiServer {

  private httpServer $httpServer;
  private Router $router;
  private bool $isRunning;

  /**
   * @param array $serverConfig
   * @throws ErrorException
   */
  public function __construct(

    #[Immutable]
    #[ArrayShape([
      "ADDRESS"      => "string",        // 서버가 리스닝할 주소 ([IP 주소]:[포트 번호] 형식)
      "HOSTNAME"     => "string",        // 서버 도메인 네임
      "APPLICATION"  => "string",        // 소프트웨어 이름
      "VERSION"      => "string",        // 소프트웨어 버전
      "TIMEZONE"     => "string"|null,   // 프로세스의 전역 타임존 (NULL이면 'GMT' 사용)
      "LOG_TERMINAL" => "string"|null,   // 로그를 출력할 터미널 (NULL이면 로그 출력 안함)
      "LOG_FILE"     => "string"|null    // 로그를 저장할 파일 경로 (NULL이면 로그 저장 안함)
    ])]
    private readonly array $serverConfig

  ) {

    // 프로세스의 전역 타임존을 설정한다.
    Time::setTimeZone($this->serverConfig["TIMEZONE"] ?? "GMT");

    // SIGHUP 시그널은 무시한다.
    PosixSignalHandler::register(PosixSignal::SIGHUP, function () {});

    // SIGINT, SIGTERM, SIGQUIT 시그널이 수신되면 서버를 종료한다.
    $shutdown = [ $this, "shutdown" ];
    PosixSignalHandler::register(PosixSignal::SIGINT, $shutdown);
    PosixSignalHandler::register(PosixSignal::SIGTERM, $shutdown);
    PosixSignalHandler::register(PosixSignal::SIGQUIT, $shutdown);

    $logTerminal = $this->serverConfig["LOG_TERMINAL"] ?? null;
    $logFile = $this->serverConfig["LOG_FILE"] ?? null;
    ApiLogger::getInstance($logTerminal, $logFile)->write(
      sprintf("Starting server at %s", $this->serverConfig["ADDRESS"])
    );

    $this->httpServer = new HttpServer(
      new StreamingRequestMiddleware(),
      new LimitConcurrentRequestsMiddleware(1000),
      new RequestBodyBufferMiddleware(512),
      new RequestBodyParserMiddleware(),
      [ $this, "handle" ]
    );

    $this->router = new Router();
    $this->defineRoutes();

    $this->isRunning = true;

  }

  private function defineRoutes(): void {

    $responseFactory = new ResponseFactory();
    $streamFactory = new StreamFactory();

    $videoController = new VideoController($responseFactory, $streamFactory, new VideoDownloader());
    $defaultController = new DefaultController($responseFactory, $streamFactory);

    $authMiddleware = new AuthMiddleware($responseFactory, $streamFactory);
    $securityMiddleware = new SecurityMiddleware($responseFactory, $streamFactory, $this->serverConfig);
    $globalHeaderMiddleware = new GlobalHeaderMiddleware($this->serverConfig);
    $logMiddleware = new LogMiddleware();

    $this->router->group(".*",
      function (Router $router) use ($videoController, $defaultController): void {
        $router->get("/video/{id}", $videoController);
        $router->get("/video/{id}/{type}", $videoController);
        $router->post("/video", $videoController);
        $router->all(".*", $defaultController);
      },
      middlewares: [ $authMiddleware, $securityMiddleware ],
      postwares:   [ $globalHeaderMiddleware, $logMiddleware ]
    );
  }

  /**
   * HTTP 요청을 라우터에게 할당한다.
   *
   * @param ServerRequestInterface $request
   * @return ResponseInterface
   */
  public function handle(ServerRequestInterface $request): ResponseInterface {
    return $this->router->run($request);
  }

  /**
   * 서버를 실행한다.
   *
   * @return void
   */
  public function run(): void {
    $this->httpServer->listen(new SocketServer($this->serverConfig["ADDRESS"]));
  }

  /**
   * 프로세스를 종료한다.
   *
   * @return void
   */
  #[NoReturn]
  public function shutdown(): void {
    if ($this->isRunning) {
      ApiLogger::getInstance()->close();
      $this->isRunning = false;
      exit(0);
    }
  }

  #[NoReturn]
  public function __destruct() {
    $this->shutdown();
  }

};