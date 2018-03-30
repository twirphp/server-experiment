<?php

namespace Twirp\ServerExperiment;

use Psr\Http\Message\ServerRequestInterface;
use Twirp\ContextSetter;
use Twirp\Exception\BadRouteException;

final class HaberdasherServer
{
    const PATH_PREFIX = '/twirp/twirphp.server_experiment.Haberdasher/';

    const PACKAGE_NAME = 'twirphp.server_experiment';
    const SERVICE_NAME = 'Haberdasher';

    /**
     * @var Haberdasher
     */
    private $haberdasher;

    public function __construct(Haberdasher $haberdasher)
    {
        $this->haberdasher = $haberdasher;
    }

    /**
     * Handle the request and return a response.
     */
    public function handle(ServerRequestInterface $req)
    {
        $ctx = $req->getAttributes();
        $ctx = ContextSetter::withPackageName($ctx, self::PACKAGE_NAME);
        $ctx = ContextSetter::withServiceName($ctx, self::SERVICE_NAME);

        if ($req->getMethod() !== 'POST') {
            $msg = sprintf('unsupported method %q (only POST is allowed)', $req->getMethod());

            throw BadRouteException::create($msg, $req->getMethod(), $req->getUri()->getPath());
        }

        switch ($req->getUri()->getPath()) {
            case '/twirp/twirphp.server_experiment.Haberdasher/MakeHat':
                return $this->handleMakeHat($ctx, $req);

            default:
                $msg = sprintf('no handler for path %q', $req->getUri()->getPath());

                throw BadRouteException::create($msg, $req->getMethod(), $req->getUri()->getPath());
        }
    }

    private function handleMakeHat(array $ctx, ServerRequestInterface $req)
    {
        $header = $req->getHeaderLine('Content-Type');
        $i = strpos($header, ';');

        if ($i === false) {
            $i = strlen($header);
        }

        switch (trim(strtolower(substr($header, 0, $i)))) {
            case 'application/json':
                return $this->handleMakeHatJson($ctx, $req);

            case 'application/protobuf':
                return $this->handleMakeHatProtobuf($ctx, $req);

            default:
                $msg = sprintf('unexpected Content-Type: %q', $req->getHeaderLine('Content-Type'));

                throw BadRouteException::create($msg, $req->getMethod(), $req->getUri()->getPath());
        }
    }

    private function handleMakeHatJson(array $ctx, ServerRequestInterface $req)
    {
        $ctx = ContextSetter::withMethodName($ctx, 'MakeHat');

        $size = new \Twirphp\Server_experiment\Size();

        $size->mergeFromJsonString((string) $req->getBody());

        $hat = $this->haberdasher->makeHat($ctx, $size);

        $data = $hat->serializeToJsonString();

        return new \GuzzleHttp\Psr7\Response(
            200,
            [
                'Content-Type' => 'application/json',
            ],
            $data
        );
    }

    private function handleMakeHatProtobuf(array $ctx, ServerRequestInterface $req)
    {
        $ctx = ContextSetter::withMethodName($ctx, 'MakeHat');

        $size = new \Twirphp\Server_experiment\Size();

        $size->mergeFromString((string) $req->getBody());

        $hat = $this->haberdasher->makeHat($ctx, $size);

        $data = $hat->serializeToString();

        return new \GuzzleHttp\Psr7\Response(
            200,
            [
                'Content-Type' => 'application/protobuf',
            ],
            $data
        );
    }
}
