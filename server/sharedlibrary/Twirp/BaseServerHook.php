<?php

namespace Twirp;

class BaseServerHook implements ServerHook
{
    /**
     * {@inheritdoc}
     */
    public function requestReceived(array $ctx)
    {
        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function requestRouted(array $ctx)
    {
        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function responsePrepared(array $ctx)
    {
        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function responseSent(array $ctx)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function error(array $ctx, Error $error)
    {
        return $ctx;
    }
}
