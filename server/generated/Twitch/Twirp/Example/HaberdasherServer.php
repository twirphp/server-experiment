<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: service.proto

namespace Twitch\Twirp\Example;

use Google\Protobuf\Internal\GPBDecodeException;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twirp\BaseServerHook;
use Twirp\Context;
use Twirp\ErrorCode;
use Twirp\RequestHandler;
use Twirp\ServerHook;
use Twirp\TwirpError;

/**
 * @see Haberdasher
 *
 * Generated from protobuf service <code>twitch.twirp.example.Haberdasher</code>
 */
final class HaberdasherServer implements RequestHandler
{
    use Protocol {
        writeError as protocolWriteError;
    }

    const PATH_PREFIX = '/twirp/twitch.twirp.example.Haberdasher/';

    /**
     * @var Haberdasher
     */
    private $svc;

    /**
     * @var ServerHook
     */
    private $hook;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var StreamFactory
     */
    private $streamFactory;

    /**
     * @param Haberdasher $svc
     * @param ServerHook|null     $hook
     * @param MessageFactory|null $messageFactory
     * @param StreamFactory|null  $streamFactory
     */
    public function __construct(
        Haberdasher $svc,
        ServerHook $hook = null,
        MessageFactory $messageFactory = null,
        StreamFactory $streamFactory = null
    ) {
        if ($hook === null) {
            $hook = new BaseServerHook();
        }

        if ($messageFactory === null) {
            $messageFactory = MessageFactoryDiscovery::find();
        }

        if ($streamFactory === null) {
            $streamFactory = StreamFactoryDiscovery::find();
        }

        $this->svc = $svc;
        $this->hook = $hook;
        $this->messageFactory = $messageFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $req
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $req)
    {
        $ctx = $req->getAttributes();
        $ctx = Context::withPackageName($ctx, 'twitch.twirp.example');
        $ctx = Context::withServiceName($ctx, 'Haberdasher');

        try {
            $ctx = $this->hook->requestReceived($ctx);
        } catch (\Twirp\Error $e) {
            return $this->writeError($ctx, $e);
        } catch (\Exception $e) {
            return $this->writeError($ctx, TwirpError::internalErrorWith($e));
        }

        if ($req->getMethod() !== 'POST') {
            $msg = sprintf('unsupported method %q (only POST is allowed)', $req->getMethod());

            return $this->writeError($ctx, Error::badRoute($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        switch ($req->getUri()->getPath()) {
            case '/twirp/twitch.twirp.example.Haberdasher/MakeHat':
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

        try {
            $ctx = $this->hook->requestRouted($ctx);
        } catch (\Twirp\Error $e) {
            return $this->writeError($ctx, $e);
        } catch (\Exception $e) {
            return $this->writeError($ctx, TwirpError::internalErrorWith($e));
        }

        $in = new Size();

        try {
            $in->mergeFromJsonString((string)$req->getBody());
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::internalError('failed to parse request json'));
        }

        try {
            $out = $this->svc->MakeHat($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::internalError('received a null response while calling MakeHat. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (\Twirp\Error $e) {
            return $this->writeError($ctx, $e);
        } catch (\Exception $e) {
            return $this->writeError($ctx, TwirpError::internalErrorWith($e));
        }

        $data = $out->serializeToJsonString();

        $body = $this->getStreamFactory()->createStream($data);

        $resp = $this->getMessageFactory()
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    private function handleMakeHatProtobuf(array $ctx, ServerRequestInterface $req)
    {
        $ctx = Context::withMethodName($ctx, 'MakeHat');

        try {
            $ctx = $this->hook->requestRouted($ctx);
        } catch (\Twirp\Error $e) {
            return $this->writeError($ctx, $e);
        } catch (\Exception $e) {
            return $this->writeError($ctx, TwirpError::internalErrorWith($e));
        }

        $in = new Size();

        try {
            $in->mergeFromString((string)$req->getBody());
        } catch (GPBDecodeException $e) {
            return $this->writeError($ctx, TwirpError::internalError('failed to parse request proto'));
        }

        try {
            $out = $this->svc->MakeHat($ctx, $in);

            if ($out === null) {
                return $this->writeError($ctx, TwirpError::internalError('received a null response while calling MakeHat. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (\Twirp\Error $e) {
            return $this->writeError($ctx, $e);
        } catch (\Exception $e) {
            return $this->writeError($ctx, TwirpError::internalErrorWith($e));
        }

        $data = $out->serializeToString();

        $body = $this->getStreamFactory()->createStream($data);

        $resp = $this->getMessageFactory()
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/protobuf')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
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

        try {
            $this->hook->error($ctx, $e);
        } catch (\Exception $e) {
            // We have three options here. We could log the error, call the Error
            // hook, or just silently ignore the error.
            //
            // Logging is unacceptable because we don't have a user-controlled
            // logger; writing out to stderr without permission is too rude.
            //
            // Calling the Error hook would confuse users: it would mean the Error
            // hook got called twice for one request, which is likely to lead to
            // duplicated log messages and metrics, no matter how well we document
            // the behavior.
            //
            // Silently ignoring the error is our least-bad option. It's highly
            // likely that the connection is broken and the original 'err' says
            // so anyway.
        }

        $this->callResponseSent($ctx);

        return $this->protocolWriteError($ctx, $e);
    }

    /**
     * Triggers response sent hook hooks.
     *
     * @param array $ctx
     */
    private function callResponseSent(array $ctx)
    {
        try {
            $this->hook->responseSent($ctx);
        } catch (\Exception $e) {
            // We have three options here. We could log the error, call the Error
            // hook, or just silently ignore the error.
            //
            // Logging is unacceptable because we don't have a user-controlled
            // logger; writing out to stderr without permission is too rude.
            //
            // Calling the Error hook could confuse users: this hook is triggered
            // by the error hook itself, which is likely to lead to
            // duplicated log messages and metrics, no matter how well we document
            // the behavior.
            //
            // Silently ignoring the error is our least-bad option. It's highly
            // likely that the connection is broken and the original 'err' says
            // so anyway.
        }
    }
}
