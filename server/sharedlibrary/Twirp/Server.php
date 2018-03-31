<?php

namespace Twirp;


use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\StreamFactory;
use Psr\Http\Message\ServerRequestInterface;

final class Server implements RequestHandler
{
    /**
     * @var RequestHandler[]
     */
    private $handlers = [];

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var StreamFactory
     */
    private $streamFactory;

    /**
     * @param MessageFactory|null $messageFactory
     * @param StreamFactory|null  $streamFactory
     */
    public function __construct(
        MessageFactory $messageFactory = null,
        StreamFactory $streamFactory = null
    )
    {
        if ($messageFactory === null) {
            $messageFactory = MessageFactoryDiscovery::find();
        }

        if ($streamFactory === null) {
            $streamFactory = StreamFactoryDiscovery::find();
        }

        $this->messageFactory = $messageFactory;
        $this->streamFactory = $streamFactory;
    }

    public function registerServer($prefix, RequestHandler $server)
    {
        $this->handlers[$prefix] = $server;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $req)
    {
        foreach ($this->handlers as $prefix => $handler) {
            if (strpos($req->getUri()->getPath(), $prefix) == 0) {
                return $handler->handle($req);
            }
        }

        $msg = sprintf('no handler for path "%s"', $req->getUri()->getPath());

        $statusCode = ErrorCode::serverHTTPStatusFromErrorCode(ErrorCode::BadRoute);

        $body = $this->streamFactory->createStream(json_encode([
            'code' => ErrorCode::BadRoute,
            'msg' => $msg,
            'meta' => [
                'twirp_invalid_route' => $req->getMethod() . ' ' . $req->getUri()->getPath(),
            ],
        ]));

        return $this->messageFactory
            ->createResponse($statusCode)
            ->withHeader('Content-Type', 'application/json') // Error responses are always JSON (instead of protobuf)
            ->withBody($body);
    }
}
