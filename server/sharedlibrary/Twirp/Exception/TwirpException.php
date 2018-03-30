<?php

namespace Twirp\Exception;

abstract class TwirpException extends \Exception
{
    protected $meta = [];

    public function setMeta($key, $value)
    {
        $this->meta[$key] = $value;
    }
}
