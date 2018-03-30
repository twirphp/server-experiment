<?php

namespace Twirp\ServerExperiment;

use Google\Protobuf\Internal\Exception as ProtobufException;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twirp\Context;
use Twirp\ErrorCode;
use Twirp\RequestHandlerInterface;
use Twirp\TwirpError;

final class HaberdasherServer implements RequestHandlerInterface
{
    const PATH_PREFIX = '/twirp/twirphp.server_experiment.Haberdasher/';

    /**
     * @var Haberdasher
     */
    private $haberdasher;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var StreamFactory
     */
    private $streamFactory;

    public function __construct(
        Haberdasher $haberdasher,
        MessageFactory $messageFactory = null,
        StreamFactory $streamFactory = null
    ) {
        if ($messageFactory === null) {
            $messageFactory = MessageFactoryDiscovery::find();
        }

        if ($streamFactory === null) {
            $streamFactory = StreamFactoryDiscovery::find();
        }

        $this->haberdasher = $haberdasher;
        $this->messageFactory = $messageFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * Handle the request and return a response.
     */
    public function handle(ServerRequestInterface $req)
    {
        $ctx = $req->getAttributes();
        $ctx = Context::withPackageName($ctx, 'twirphp.server_experiment');
        $ctx = Context::withServiceName($ctx, 'Haberdasher');

        if ($req->getMethod() !== 'POST') {
            $msg = sprintf('unsupported method %q (only POST is allowed)', $req->getMethod());

            return $this->writeError($ctx, Error::badRoute($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        switch ($req->getUri()->getPath()) {
            case '/twirp/twirphp.server_experiment.Haberdasher/MakeHat':
                return $this->handleMakeHat($ctx, $req);

            default:
                $msg = sprintf('no handler for path %q', $req->getUri()->getPath());

                return $this->writeError($ctx, Error::badRoute($msg, $req->getMethod(), $req->getUri()->getPath()));
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

                return $this->writeError($ctx, Error::badRoute($msg, $req->getMethod(), $req->getUri()->getPath()));
        }
    }

    private function handleMakeHatJson(array $ctx, ServerRequestInterface $req)
    {
        $ctx = Context::withMethodName($ctx, 'MakeHat');

        $size = new \Twirphp\Server_experiment\Size();

        try {
            $size->mergeFromJsonString((string)$req->getBody());
        } catch (ProtobufException $e) {
            return $this->writeError($ctx, TwirpError::internalError('failed to parse request json'));
        }

        try {
            $hat = $this->haberdasher->makeHat($ctx, $size);
        } catch (\Twirp\Error $e) {
            return $this->writeError($ctx, $e);
        } catch (\Exception $e) {
            return $this->writeError($ctx, TwirpError::internalErrorWith($e));
        }

        $data = $hat->serializeToJsonString();

        $body = $this->streamFactory->createStream($data);

        return $this->messageFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);
    }

    private function handleMakeHatProtobuf(array $ctx, ServerRequestInterface $req)
    {
        $ctx = Context::withMethodName($ctx, 'MakeHat');

        $size = new \Twirphp\Server_experiment\Size();

        try {
            $size->mergeFromString((string)$req->getBody());
        } catch (ProtobufException $e) {
            return $this->writeError($ctx, TwirpError::internalError('failed to parse request proto'));
        }

        try {
            $hat = $this->haberdasher->makeHat($ctx, $size);
        } catch (\Twirp\Error $e) {
            return $this->writeError($ctx, $e);
        } catch (\Exception $e) {
            return $this->writeError($ctx, TwirpError::internalErrorWith($e));
        }

        $data = $hat->serializeToString();

        $body = $this->streamFactory->createStream($data);

        return $this->messageFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/protobuf')
            ->withBody($body);
    }

    /**
     * Writes Twirp errors in the response and triggers hooks.
     *
     * @param array        $ctx
     * @param \Twirp\Error $e
     *
     * @return ResponseInterface
     */
    private function writeError(array $ctx, \Twirp\Error $e)
    {
        $statusCode = ErrorCode::serverHTTPStatusFromErrorCode($e->code());
        $ctx = Context::withStatusCode($ctx, $statusCode);

        $body = $this->streamFactory->createStream(json_encode([
            'code' => $e->code(),
            'msg' => $e->msg(),
            'meta' => $e->metaMap(),
        ]));

        return $this->messageFactory
            ->createResponse($statusCode)
            ->withHeader('Content-Type', 'application/json')// Error responses are always JSON (instead of protobuf)
            ->withBody($body);
    }
}
