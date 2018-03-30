<?php

namespace Twirp\ServerExperiment;

use GuzzleHttp\Psr7\Response;
use Twirp\Context;
use Twirp\ErrorCode;
use Twirp\TwirpError;

final class Error
{
    /**
     * Used when the twirp server cannot route a request.
     *
     * @param string $msg
     * @param string $method
     * @param string $url
     *
     * @return TwirpError
     */
    public static function badRoute($msg, $method, $url)
    {
        $e = TwirpError::newError(ErrorCode::BadRoute, $msg);
        $e->withMeta('twirp_invalid_route', $method . ' ' . $url);

        return $e;
    }

    public static function write(array $ctx, \Twirp\Error $e)
    {
        $statusCode = ErrorCode::serverHTTPStatusFromErrorCode($e->code());
        $ctx = Context::withStatusCode($ctx, $statusCode);

        return new Response(
            $statusCode,
            [
                'Content-Type' => 'application/json', // Error responses are always JSON (instead of protobuf)
            ],
            self::marshal($e)
        );
    }

    /**
     * Returns JSON from a twirp Error, that can be used as HTTP error response body.
     * If serialization fails, it will use a descriptive Internal error instead.
     *
     * @param \Twirp\Error $e
     *
     * @return string
     */
    public static function marshal(\Twirp\Error $e)
    {
        return json_encode([
            'code' => $e->code(),
            'msg' => $e->msg(),
            'meta' => $e->metaMap(),
        ]);
    }
}
