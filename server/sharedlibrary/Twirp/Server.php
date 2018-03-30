<?php

namespace Twirp;


use Psr\Http\Message\ServerRequestInterface;

final class Server implements RequestHandler
{
    /**
     * @var RequestHandler[]
     */
    private $handlers = [];

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

        $msg = sprintf('no handler for path %q', $req->getUri()->getPath());

        return Error::write([], Error::badRoute($msg, $req->getMethod(), $req->getUri()->getPath()));
    }
}
