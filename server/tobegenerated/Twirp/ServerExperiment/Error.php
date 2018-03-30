<?php

namespace Twirp\ServerExperiment;

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
}
