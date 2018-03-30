<?php

namespace Twirp\Exception;

final class BadRouteException extends TwirpException
{
    public static function create($message, $method, $url)
    {
        $e = new self($message);

        $e->meta['twirp_invalid_route'] = $method.' '.$url;

        return $e;
    }
}
